<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Réinitialisation du mot de passe</title>
</head>
<body style="margin:0;padding:0;background-color:#e8eef0;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#10231f;-webkit-font-smoothing:antialiased;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#e8eef0;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;background-color:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #d7e4e1;box-shadow:0 12px 32px rgba(16,35,31,0.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#0f766e 0%,#0d9488 55%,#14b8a6 100%);padding:28px 32px;text-align:left;">
                            <p style="margin:0;font-size:13px;letter-spacing:0.08em;text-transform:uppercase;color:rgba(255,255,255,0.82);font-weight:600;">
                                MarketPlace
                            </p>
                            <h1 style="margin:8px 0 0;font-size:24px;line-height:1.3;color:#ffffff;font-weight:700;">
                                Réinitialisation du mot de passe
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.6;color:#10231f;">
                                Bonjour{{ ! empty($name) ? ' '.$name : '' }},
                            </p>
                            <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:#3d524e;">
                                Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte
                                <strong style="color:#0f766e;">MarketPlace</strong>.
                            </p>
                            <p style="margin:0 0 28px;font-size:15px;line-height:1.7;color:#3d524e;">
                                Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe.
                                Ce lien est valable <strong>{{ $expire }} minutes</strong>.
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 28px;">
                                <tr>
                                    <td align="center" bgcolor="#0f766e" style="border-radius:999px;">
                                        <a href="{{ $url }}"
                                           style="display:inline-block;padding:14px 28px;font-size:15px;font-weight:700;color:#ffffff;text-decoration:none;border-radius:999px;background-color:#0f766e;">
                                            Réinitialiser mon mot de passe
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 10px;font-size:13px;line-height:1.6;color:#6b7f7a;">
                                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :
                            </p>
                            <p style="margin:0 0 24px;font-size:12px;line-height:1.6;word-break:break-all;">
                                <a href="{{ $url }}" style="color:#0f766e;text-decoration:underline;">{{ $url }}</a>
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f3faf8;border:1px solid #d7e4e1;border-radius:12px;">
                                <tr>
                                    <td style="padding:14px 16px;">
                                        <p style="margin:0;font-size:13px;line-height:1.6;color:#3d524e;">
                                            Si vous n’avez pas demandé cette réinitialisation, ignorez simplement cet e-mail.
                                            Votre mot de passe actuel restera inchangé.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 32px 28px;border-top:1px solid #e6efed;text-align:center;background-color:#fbfcfc;">
                            <p style="margin:0 0 4px;font-size:14px;font-weight:700;letter-spacing:0.02em;color:#0f766e;">
                                MarketPlace Vitrine
                            </p>
                            <p style="margin:0;font-size:12px;line-height:1.6;color:#6b7f7a;">
                                Des produits soigneusement exposés, une mise en relation simple et claire.
                            </p>
                        </td>
                    </tr>
                </table>

                <p style="margin:18px 0 0;font-size:11px;line-height:1.5;color:#8a9b97;">
                    Cet e-mail a été envoyé automatiquement, merci de ne pas y répondre.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
