# SAE_Dev_App_Securisee
### Realised by ARNAUD Elian, BRESSON Jules, CARLIER Maxime, PELLIZZARI Théo et RENARD Tanguy

## Basic features :
(Here your have the two tables of the features of the project, you can see if the feature was done or not and by whom).

| Feature                                                                                    | Author        | Status      |
|--------------------------------------------------------------------------------------------|---------------|-------------|
| 1. Identification/Authentification – Login form                                            | Tanguy        | ok          |
| 2. Registration on the platform                                                            | Elian         | ok          |
| 3. Display the series catalog                                                              | Maxime,Theo   | ok          |
| 4. Detailed display of a series and the list of its episodes                               | Maxime,Theo   | ok          |
| 5. Display/watch an episode of a series                                                    | Maxime        | ok          |
| 6. Add a series to the list of preferences of a user                                       | Elian         | ok          |
| 7. User home page: display his favorite series                                             | Elian         | ok          |
| 8. When watching an episode, automatically add the series to the user's "in progress" list | Maxime        | ok          |
| 9. When watching an episode of a series, rate and comment the series                       | Jules, Maxime | ok          |
| 10. When displaying a series, indicate its average rating and give access to comments      | Tanguy, Jules | In progress |

## Extended features :
| Feature                                                                                           | Author       | Status |
|---------------------------------------------------------------------------------------------------|--------------|--------|
| 11. Account activation                                                                            | ?            | No     |
| 17. manage the user profile: add information (name, first name, preferred genre ...)              | Tanguy       | ok     |
| 12. Search in the catalog by keywords                                                             | Tanguy, Théo | ok     |
| 13. Sort in the catalog                                                                           | ?            | No     |
| 14. filter the catalog by genre, by public                                                        | ?            | No     |
| 15. manage the list of preferences: removal                                                       | Jules        | ok     |
| 16. Manage the "already watched" list                                                             | ?            | No     |
| 18. direct access to the episode to watch when viewing a series that is in the "in progress" list | ?            | No     |
| 19. Sort in the catalog according to the average rating                                           | ?            | No     |
| 20. Sort in the catalog according to the number of comments                                       | ?            | No     |

## Explanation of the features present on the application :
### 1. Identification/Authentification – Login form (Author : Tanguy)
The user can log in to the platform with his username and password. If the user does not have an account, he can register on the platform.

### 2. Registration on the platform (Author : Elian)
The user can register on the platform by filling in a form with his email address, his password and his password confirmation.

### 3. Display the series catalog (Author : Maxime, Theo)
The user can view the catalog of series available on the platform. The catalog is displayed in the form of a list of series. Each series is represented by a title, a poster and a short description.

### 4. Detailed display of a series and the list of its episodes (Author : Maxime, Theo)
The user can view the details of a series. The details of a series are displayed in the form of a page containing the title, the poster, the description, the genre, the targeted public, the year of release, the date of addition on the platform and the number of episodes.
There also is a list of episodes of the series. Each episode is represented by a number, a title, a poster and a duration.

### 5. Display/watch an episode of a series (Author : Maxime)
The user can view the details of an episode. The details of an episode are displayed in the form of a page containing the number, the title, the poster, the duration, the description of the episode and the video.

### 6. Add a series to the list of preferences of a user (Author : Elian)
The user can add a series to his list of preferences. The series is added to the list of preferences of the user.

### 7. User home page: display his favorite series (Author : Elian)
The user can view his list of preferences on his home page. It's possible to click on a series to view its details.
The home page is display automatically when the user is logged in or when he clicks on a "Home" button.

### 8. When watching an episode, automatically add the series to the user's "in progress" list (Author : Maxime)
When the user watches an episode, the series is automatically added to the list of series in progress of the user.
This list is displayed on the home page of the user like the list of preferences.

### 9. When watching an episode of a series, rate and comment the series (Author : Jules, Maxime)
When the user watches an episode of a series, he can rate and comment the series.

### 10. When displaying a series, indicate its average rating and give access to comments (Author : Tanguy, Jules)
When the user displays a series, he can see the average rating of the series and access the comments of the series. (access to the comments is not yet implemented)

### 17. manage the user profile: add information (name, first name, preferred genre ...) (Author : Tanguy)
The user can manage his profile by adding information (name, first name, preferred genre ...).

### 12. Search in the catalog by keywords (Author : Tanguy, Théo)
The user can search in the catalog by keywords. The search is done on the title and the description of the series.

### 15. manage the list of preferences: removal (Author : Jules)
The user can remove a series from his list of preferences.

## How it works :
In order to use the application, you must first create an account.
To create an account, you must enter an email address and a password.  
Once you have created your account, you can log in and start using the application.  

You can click on the "Catalogue" button to see the list of series available on the platform.  
You can then search for a series with some keywords and click on the "Details" button of a series to see all the episodes of the series.  
Then you just have to click on the "Regarder" button of an episode to watch it.  
Here you can rate the series, comment on it and check the average rating of a series.  
And you can add a series to your favorites.  
You can watch the episodes of the series you have added to your favorites from the home page.  
If you haven't finished watching a series, you can go back to it later and continue watching it at the same point where you left it.  
Finally you can remove a series from your favorites by clicking on the "Supprimer des favoris" button.  

You can return to the home page by clicking on the "Accueil" button, or you can continue to browse site features.  
You can also click on the "NetVOD" logo to return to the "Catalogue" page.  
In the "Accueil" page, you can see the list of your favorite series and your watchlist.  

So you can click on the "Mon compte" button to access your account page.  
On this page you can put your name, your first name and your favorite genre.  
You can click on the scrallbar to change your favorite genre.

If you have finished to use the application, you can click on the "Déconnexion" button to log out.