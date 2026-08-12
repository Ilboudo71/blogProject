<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLike;
use App\Models\User;
use App\Support\MarketplaceVisitor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function home(Request $request): View
    {
        $type = $request->string('type')->toString();
        $search = $request->string('q')->toString();

        $products = Product::query()
            ->published()
            ->with('user')
            ->when($type !== '', fn ($query) => $query->where('type_produits', $type))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('marketplace.home', [
            'products' => $products,
            'types' => Product::typeLabels(),
            'activeType' => $type,
            'search' => $search,
            'likedProductIds' => $this->likedProductIds($request, $products->getCollection()->pluck('id')),
        ]);
    }

    public function show(Request $request, Product $product): View|RedirectResponse
    {
        if (! $product->isPublished()) {
            abort(404);
        }

        $product->recordView(MarketplaceVisitor::key($request));
        $product->load('user');

        $related = Product::query()
            ->published()
            ->with('user')
            ->where('type_produits', $product->type_produits)
            ->whereKeyNot($product->getKey())
            ->latest('published_at')
            ->limit(4)
            ->get();

        $likedIds = $this->likedProductIds(
            $request,
            $related->pluck('id')->push($product->id)
        );

        return view('marketplace.show', [
            'product' => $product,
            'related' => $related,
            'likedProductIds' => $likedIds,
            'isLiked' => $likedIds->contains($product->id),
        ]);
    }

    public function loginRedirect(): RedirectResponse
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            return redirect()->to($user->panelHomeUrl());
        }

        return redirect()->to('/user/login');
    }

    public function about(): View
    {
        return view('marketplace.about');
    }

    public function privacy(): View
    {
        return view('marketplace.privacy');
    }

    public function terms(): View
    {
        return view('marketplace.terms');
    }

    /**
     * @param  Collection<int, int|string>  $productIds
     * @return Collection<int, int>
     */
    private function likedProductIds(Request $request, Collection $productIds): Collection
    {
        $productIds = $productIds->filter()->values();

        if ($productIds->isEmpty()) {
            return collect();
        }

        $visitorKey = MarketplaceVisitor::key($request);

        return ProductLike::query()
            ->where('visitor_key', $visitorKey)
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id);
    }
}
