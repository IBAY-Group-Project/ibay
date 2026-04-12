# ibay
Version 1.0.0

# What we have:

    * homepage, login page and sign up page
    * login form on login.html goes to login.php (empty)
    * signup form on signup.html goes to signup.php
        - has password validation between password input and confirm_password input



## Provisional To-do list from v1.0.0 until v2.0.0??? :

# JavaScript:
    1 - Carousel functionality
    2 - When signed in: Sell button goes to sell.html , otherwise prompts log in / sign up
    3 - 

# PHP:
    1 - save user data into database
    2 - 

# HTML/CSS:
    1 - Sell page
    2 - Search page
    3 - Item page
    4 - Basket page
    5 - Checkout page 
    6 - 

# Other
    * Test user: Username: test     Password: test
        - should be a normal user

    * Admin: Username: Admin        Password: 1 or Admin123 (????)
        - user with admin capabilities (View all users / items / orders , moderation , etc )

    * 

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

# Workflow

 Work locally and save your work onto your own individual branch and we can merge all branches periodically onto the main branch
 Document in your individual branch readme 


# Assigments ????

* Mohamed - HTML / CSS
* Adam - JS
* Dan - PHP / MySQL 


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


ibay logo (official ebay logo isnt consistent in some html files) needs to be ibay.svg some have ebay.svg



items on search results and in the item page should have 2 images and ideally carousel functionality, currently has just 1

no filter or sorting on search results,

10 items hardcoded into search.html, should be dependant on search query

product1 hardcoded into item.html, should be dependant on selected item from the search results

no dedicated back button / clicking the ebay logo takes you back to the homepage

clear logic between what you can do in the 2 states (Guest / Logged In) - you need to be logged in to add to basket / sell items but can view items as a guest

might want recommended items on the item page / home page depending on user data

grouped all buttons under .button class, differentiated in stylesheet by id

basket / checkout button in the header 

on the item page considering tabs vs accordion style 

checkout.html include the subtotal ( price x quantity) and maybe exclude the price in the item details












