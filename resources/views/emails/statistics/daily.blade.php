<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика — {{ config('app.name') }}</title>
</head>
<body style="margin:0; padding:24px 12px; background:#151515; color:#f3f1eb; font-family:Arial,sans-serif; line-height:1.6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background:#1d1d1d; border:1px solid #383735; border-radius:16px;">
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 24px; color:#d4bf99; font-family:Georgia,serif; font-size:22px;">{{ config('app.name') }}</p>
                            <h1 style="margin:0 0 12px; color:#f3f1eb; font-family:Georgia,serif; font-size:28px; line-height:1.2;">Статистика за день</h1>
                            <p style="margin:0 0 24px; color:#aaa79f;">{{ $date }} · {{ config('statistics.timezone') }}</p>
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; color:#f3f1eb;">
                                <tr>
                                    <td style="padding:16px 0; border-top:1px solid #383735;">Просмотры новостей</td>
                                    <td align="right" style="padding:16px 0; border-top:1px solid #383735; color:#d4bf99; font-size:26px; font-weight:bold;">{{ $views }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 0; border-top:1px solid #383735; border-bottom:1px solid #383735;">Новые комментарии</td>
                                    <td align="right" style="padding:16px 0; border-top:1px solid #383735; border-bottom:1px solid #383735; color:#d4bf99; font-size:26px; font-weight:bold;">{{ $comments }}</td>
                                </tr>
                            </table>
                            <p style="margin:24px 0; color:#aaa79f; font-size:14px;">Данные с начала дня на момент отправки. Повторные открытия статей учитываются отдельно. В число новых комментариев входят также ожидающие модерации.</p>
                            <a href="{{ route('articles.index') }}" style="display:inline-block; padding:12px 18px; border-radius:8px; background:#d4bf99; color:#151515; text-decoration:none; font-weight:bold;">Открыть новости</a>
                            <p style="margin:24px 0 0; color:#aaa79f; font-size:13px;">Вы получили этот отчёт как модератор {{ config('app.name') }}.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
