# Yandex.Metrica

## Что делает этот код?
Код в этом репозитории помогает записать из csv файла в Яндекс.Метрику данные о заказах клиентов.

## Как пользоваться?
1. Создайте файлы: 
    + `.secret/counter.txt`
    + `.secret/token.txt`
    + `.secret/auth/client_id.txt`
    + `.secret/auth/client_secret.txt`
    + `.secret/auth/code.txt`
2. Перейдите по https://oauth.yandex.ru/ c нужного аккаунта
3. На сайте выбирите из приложений `yandex-metrica-csv`
4. Впишите `ClientID` из сайта в `.secret/auth/client_id.txt`
5. Впишите `Client secret` из сайта в `.secret/auth/client_secret.txt`
6. Перейдите по ссылке https://oauth.yandex.ru/authorize?response_type=code&client_id=400e1d19e1d04118a45c133081d72333
7. Затем вставьте код подтверждения в файл `.secret/auth/code.txt`
8. Запустите в консоли команду `php auth.php`. Вы должны находится в папке с проектом в момент
запуска. Либо же прописать абсолютный путь в команде для файла `auth.php`.
9. Скопируйте json из консоли и вставьте его в `.secret/token.txt`
10. Вставьте номер счетчика метрики в `.secret/counter.txt`
11. Перед тем как запустить загрузку в метрику, убедитесь, что csv файл имеет separator `,`
и enclosure `"`. Также файл должен содержать заголовки на 1 строке и соответствовать документации
https://yandex.ru/dev/metrika/doc/api2/crm/data/uploadsimpleorders.html
12. Далее запустите команду в терминале `php code-push.php -f <путь до файла>`
13. Результат вызова будет записан в code-push-result.log