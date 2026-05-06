# ibay
Version 1.1.0



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



items on search results and in the item page should have 2 images and ideally carousel functionality, currently has just 1

no filter or sorting on search results

10 items hardcoded into search.html, should be dependant on search query

product1 hardcoded into item.html, should be dependant on selected item from the search results

no dedicated back button / clicking the ebay logo takes you back to the homepage

clear logic between what you can do in the 2 states (Guest / Logged In) - you need to be logged in to add to basket / sell items but can view items as a guest

might want recommended items on the item page / home page depending on user data

grouped all buttons under .button class, differentiated in stylesheet by id

basket / checkout button in the header 

on the item page considering tabs vs accordion style 

checkout.html include the subtotal ( price x quantity) and maybe exclude the price in the item details

checkout is again hardcoded with product1 and might need a layout change when actual items are used to keep everything in one screen

quantity button in the checkout needs + - functions



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

06/05/2026 10:29

has basket details autofill 
basket details can save to account / database
google sign in 

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



- make sign up add a username too
    - make sign in top button either user or email not just email ///
    - also add quantity of items that the seller has avaiable in the ibayitems table and also in sell.php form

- change all html to php ///

- change everything from hardcode to db dependent 

- add a         <?php if ($is_logged_in = isset($_SESSION['email'])): ?>          on sell.php to tell user they need to sign in to sell

- on search.php make it max 8 per page and fix the logic ////

- sort out the images on the item / sell  / search page still has placeholders

- homepage is stll hardcoded

- thers no validation on auction end date on sell page  (or postcode)

- prompt to finish setting up account before checkout  / save the info 

- add an auto address finder when filling out

- add My Orders page through the Account page //











- try to add singing in with google / apple etc

- try to add paying with visa debit / credit  / mastercard etc

- ai chatbot

- auction

- page redirects

-postcode.io api for postcode validation

- email verification sendgrid

http://localhost/ibay/order_success.php?orderId=1778004555

