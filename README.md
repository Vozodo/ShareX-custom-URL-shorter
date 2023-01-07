# Sharex Custom PHP URL Shorter

## Requirements
- Minimal PHP Version: 8.0
- PHP Extenstions:
    - pdo_sqlite
    - mod_rewrite
- Webserver with .htaccess enabled
- Git installed on you're Server

## Installation
### Server Config
1. Clone the Project to you're Webserver
```
git clone https://github.com/Vozodo/ShareX-custom-URL-shorter
```
2. Open **index.php** and Change the Settings

### ShareX Config
1. Go to Destinations > Custom uploader settings...
2. Click on New and give the uploader a name, e.g. **phpshorter**.
3. For the Destination type select **URL shortener**
4. The method must be **POST**
5. For the Request URL enter the URL to your web server
6. Under the Body, select "**Frome data (multipart/form-data)**" from the dropdown list.
7. In the table below write two entries:

| Name | Value |
|---|---|
| url | {input} |
| secret | YOUR SET PASSWORD |

8. For the file form name enter ```sharex```

### Test
At the bottom left you can select the uploader you want to create in the URL shortener. Click on Test. A new window will open and if everything worked, it will return a shortened link.

Translated with www.DeepL.com/Translator (free version)