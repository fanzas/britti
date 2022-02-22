To establish the best discount I am sorting discounts array to give the best possible value per item.

As the app will be expanding it is worth putting processBasket as a class so it serves one purpose which is giving the end basket structure and utilize different classes, so it can do more things than just process discounts without making this function too big for a controller function.
<br>
<br>
No setters and getters and no sanitization. I used public properties on everything, but that wouldn't have been the case in live application. I am not accepting any user input.
<br>
<br>
If the desirable structure was to put Special Price as a property on Product object, please let me know. I can adjust my code, but I believe it is better to keep those apart, as I imagine they would've been in the database.
<br>
<br>
I've spent around 3 hours on the task including installing apachee server, phpunit and cleaning up the code, so this is not the ideal structure for the application.
Because of the time limit I wasn't worried about input sanitization as well as implementing class inheritance.

