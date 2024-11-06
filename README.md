A complete solution for E-commerce Business with exclusive features & super responsive layout

### Configure product bulk-upload

-   download [Bulk_Upload_Template.xlsm](https://cdn.discordapp.com/attachments/1297514701931937846/1301150934042415154/Bulk_Upload_Template.xlsm?ex=67236ebc&is=67221d3c&hm=ed154223180f7afa6565fad990f4e0a8b74d23f9b59d18682ec025dccfdfd40a&) file.
-   put the file in `public/buxl`.
-   download [buxlgen-0.1.0.jar](https://drive.google.com/file/d/1EEdkTIt7tYENOBCzLLcP0kp5cL1Or9Rm/view?usp=sharing) file & run it (it requires java 23) with the command: `java -jar buxlgen-0.1.0.jar`.
-   set BULK_UPLOAD_SERVER_URL to `http://localhost:9115/bu/xlgen` in your `.env` file
-   run `php artisan queue:work` to process the jobs
