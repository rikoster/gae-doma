DOMA - Digital Orienteering Map Archive

PHP/MySQL website for storage and display of orienteering maps.

By _Mats Troeng_ in (https://github.com/matstroeng/doma)
 * All the visible functionality is by Mats & co.! (Good stuff)
 * This code is about some adaptations to the plumbing

### Fork by rikoster on 2020-12-13

This GAE version of DOMA is adapted for Google Application Engine serverless
environment. 
 * All maps are stored in Google Cloud Storage in a different location from the
   source code. 
 * The setup to use Google SQL Cloud is somewhat custom compared to normal
   parameters to SQL server. Further, a switch from MyISAM to InnoDB is
   required.
 * Sending email in GAE requires Mailjet
 * GAE environment requires a front controller and app.yaml -configuration file
 * In reading jpeg metadata, it is necessary to replace fread with
   stream_get_contents -function for the purpose of reading a large section from
   Google Cloud Storage

Beyond these five main sources of change, there are two other small changes
 * All urls to Google Maps API use now https
 * The Google Analytics helper function has been adapted to the up-to-date GTAG
   architecture
 
Instructions for setting up DOMA in Google App Engine environment
-----------------------------------------------------------------

1. [Download Google Cloud SDK](https://cloud.google.com/sdk/docs/install)

2. [Create a Google Cloud project](https://cloud.google.com/resource-manager/docs/creating-managing-projects)
   - You need to connect the project to a billing account, otherwise deployment
     will not work

3. [Make sure you have access to a Google SQL Cloud MySQL Instance](https://cloud.google.com/sql/docs/mysql/quickstart)

4. [Create a non-root database user for the purposes of DOMA](https://cloud.google.com/sql/docs/mysql/create-manage-users)

5. [Create a database in your Google SQL Cloud Instance for DOMA](https://cloud.google.com/sql/docs/mysql/create-manage-databases)

6. [Create an app for your project](https://cloud.google.com/appengine) 

7. Enable Cloud SQL Admin API
   - The following command avalable in Google Cloud SDK does it

   * gcloud services enable sqladmin.googleapis.com

8. [Locate your default Google Cloud Storage Bucket](https://cloud.google.com/appengine/docs/standard/php7/using-cloud-storage)
   - Typically has the address gs://YOUR_PROJECT_ID.appspot.com

9. Change the default Access Control List (ACL) of that bucket as follows (also
   unauthenticated visitors need to see the maps)
   - The following command avalable in Google Cloud SDK does it

   - gsutil defacl ch -u AllUsers:R gs://YOUR_PROJECT_ID.appspot.com

10. Configure your app ServiceAccount so it can access both the SQL Database and
    the Google Cloud Storage Bucket
    * The following Google Cloud SDK commands do it, you can also check
      how-to-guides

    * gcloud projects add-iam-policy-binding YOUR_PROJECT_ID --member serviceAccount:YOUR_PROJECT_ID@appspot.gserviceaccount.com --role=roles/cloudsql.client
    * gsutil iam ch serviceAccount:YOUR_PROJECT_ID@appspot.gserviceaccount.com:admin gs://YOUR_PROJECT_ID.appspot.com
    * gsutil iam ch serviceAccount:YOUR_PROJECT_ID@appspot.gserviceaccount.com:objectAdmin gs://YOUR_PROJECT_ID.appspot.com

11. Enable Maps Javascript API (needed for DOMA functionality). See also about
    getting an API key (some details in the config.php file)
    - gcloud services enable maps-backend.googleapis.com

12. (optional) For local use, create another ServiceAccount and a credential
    file for it that can be downloaded as a .json file
    - [See e.g. here](https://cloud.google.com/sdk/gcloud/reference/iam/service-accounts)

13. (optional) Authorize the other ServiceAccount to your cloud storage
    * gsutil iam ch serviceAccount:YOUR_CUSTOM_ACCOUNT@appspot.gserviceaccount.com:admin gs://YOUR_PROJECT_ID.appspot.com

14. (optional) [For local access to your Cloud SQL database, install Cloud SQL Proxy](https://cloud.google.com/sql/docs/mysql/sql-proxy)
 
15. Install *Composer* (https://getcomposer.org/) for your local php if you don't have yet

16. Install Google Cloud storage libraries at the src folder
    - composer require google/cloud-storage

17. Install Mailjet libraries at the src folder. See also about getting a
    Mailjet account if you want to send email (there are some details in the
    config_original.php file)
    - composer require mailjet/mailjet-apiv3-php

18. Save the config-original.php as config.php and edit it to enter your
    database info, username, password etc.

19. Deploy the app to Google App engine like this:
    - gcloud app deploy

20. You can access you DOMA site like this:
    - gcloud app browse

--> Your DOMA site is ready for the first use.
