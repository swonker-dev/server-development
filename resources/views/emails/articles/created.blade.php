<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новая статья — {{ config('app.name') }}</title>
</head>
<body style="margin:0; padding:24px 12px; background:#151515; color:#f3f1eb; font-family:Arial,sans-serif; line-height:1.6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background:#1d1d1d; border:1px solid #383735; border-radius:16px;">
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 24px; color:#d4bf99; font-family:Georgia,serif; font-size:22px;">{{ config('app.name') }}</p>
                            <p style="margin:0 0 8px; color:#aaa79f;">На сайте опубликована новая статья.</p>
                            <h1 style="margin:0 0 16px; font-family:Georgia,serif; font-size:28px; line-height:1.2;">{{ $article->title }}</h1>
                            <p style="margin:0 0 24px; color:#f3f1eb; white-space:pre-line;">{{ $article->description }}</p>
                            <p style="margin:0 0 24px; color:#aaa79f; font-size:14px;">{{ $article->created_at->format('d.m.Y H:i') }}</p>
                            <a href="{{ route('articles.show', $article) }}" style="display:inline-block; padding:12px 18px; border-radius:8px; background:#d4bf99; color:#151515; text-decoration:none; font-weight:bold;">Открыть статью</a>
                            <p style="margin:24px 0 0; color:#aaa79f; font-size:13px;">Вы получили это уведомление как модератор {{ config('app.name') }}. Для просмотра статьи войдите в свой аккаунт.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
