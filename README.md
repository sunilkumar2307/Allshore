# Allshore
Allshore Assignment

This API Gateway is implemented with all bellow provided requirements given

1) consider storing Barrowing data in a table named borrowings, which would include fields like user_id, book_id, checkout_date, and checkin_date. 
2) A book can be considered unavailable if the checkin_date is null and not allowed to Barrowing again.
3) Ensuring the test includes a scenario where a book cannot be borrowed twice.
4) Limiting users to a maximum of five borrowed books.

--Application Configuration--

Application folder is inside the provided Zip file with the name 'allshore'.

1) Current application is using database layer are MariaDB with the User/password as root/root with the database name 'allshore'. You cant change these configurable values found at root path '.env' as per your database layer.  
2) Run the symfony at project location: 1) cd allshore/ 2) symfony server:start
3) Once the application is running at Http://localhost:8000, 
4) Run 'php bin/console doctrine:migrations:migrate' at New Terminal to generate all required database resources.
5) Once the migration is successful, use the Postman app to test the Application.


--Use postman application to test various functionalities/API end points. All the endpoint URL's provided bellow 

1) Create New User: http://127.0.0.1:8000/user/create/{name} (where as last parameter is a query parameter. For each new user creation you have to give a new name): Example: http://127.0.0.1:8000/user/create/Sunilkumar
2) Get All Users: http://127.0.0.1:8000/users (Which will show all available users in the system)
3) Delete All Users: http://localhost:8000/deleteallusers (Which will delete all the users in the system)

4) Create New Book: http://127.0.0.1:8000/book/create/{bookname}/{authorname} (where as last parameters are a query parameter. For each new Book creation you have to give a BookName, AutherName. Combination on BookName and AutherName is a Unique entity) Example:http://127.0.0.1:8000/book/create/Book1/Author1
5) Get All Books: http://127.0.0.1:8000/books (Which will show all available books in the system)
6) Delete All Books: http://localhost:8000/deleteallbooks (Which will delete all books in the system)

Note: Please create good amount of data (Users, Books) Using provided API links before tesing Borrowing functionality. You can use postman.
8) Borrow Book: http://127.0.0.1:8000/barrowbook/{userId}/{bookId} (where as last parameters are query parameter for "book-id and author-id" must be exist and should follow all required sceneries provided for this application.  Example: http://127.0.0.1:8000/barrowbook/1/5
9) Get All Borrowings: http://127.0.0.1:8000/borrowings (Which will show all Borrowings in the system)
10) Check-in Book: http://127.0.0.1:8000/checkinbook/{userId}/{bookId} (where as last parameters are query parameter for "book-id and author-id". provided book should be borrowed by same user and should not check-in already) Ex: http://127.0.0.1:8000/checkinbook/1/5
11) Delete All Borrowings: http://localhost:8000/deleteallborrowings (Which will delete all Borrowing Records in the system)

All postman requests are exported as postman collection in "allshore.postman_collection.json" file available in provided zip file. You can import this in to postman and test the API End points once the application is configured and running.



