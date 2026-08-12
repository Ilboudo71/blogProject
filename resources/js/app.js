import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.querySelectorAll('[data-like-button]').forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            event.stopPropagation();

            if (button.dataset.loading === '1') {
                return;
            }

            const url = button.dataset.likeUrl;
            if (!url) {
                return;
            }

            button.dataset.loading = '1';
            button.classList.add('is-loading');

            try {
                const response = await window.axios.post(url, null, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        Accept: 'application/json',
                    },
                });

                const { liked, likes_count: likesCount } = response.data;
                const countEl = button.querySelector('[data-like-count]');

                button.classList.toggle('is-liked', Boolean(liked));
                button.setAttribute('aria-pressed', liked ? 'true' : 'false');
                button.setAttribute('aria-label', liked ? 'Retirer le like' : 'Aimer ce produit');

                if (countEl) {
                    countEl.textContent = new Intl.NumberFormat('fr-FR').format(Number(likesCount || 0));
                }
            } catch (error) {
                console.error('Like error', error);
            } finally {
                button.dataset.loading = '0';
                button.classList.remove('is-loading');
            }
        });
    });
});
