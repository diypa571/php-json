######   Hej! Det finns fyra filer. (db.json, index.php, state.log,with_test_cases.php)
###### Från början tänkte jag fel, tänkte skapa en UI för API tjänsten med sessions variabler, en kundvagn att kunden kan lägga till sina varor i kundvagn.
######  Men sedan märkte jag att det behövs inte. Testet som jag uppfattat nu är att skapa en API tjänst för att fylla på json filen med antal ett antal varor, det beroende på varan och antal varor som kunderna handlar.
  
###### index filen, den innehåller funktionerna, metoderna som används för API-tjänsten.
###### När det gäller db.json, jag tänker en mysql data tabell som innehåller olik artiklar och antalet tillgängliga produkter, har försökt att inte ändra mycket i filen.
###### Filen state.log, i den lagras data beroende på den API request som skickar. 
 


###### Filen with_test_cases.php, den finns för att kunna testa! Det går att testa på följande sätt. URL: with_test_cases.php?test Det är viktigt att titta på filen state.log, för att success eller inte kommer det att lagras i state.log filen
 


###### index.php?route=data
###### index.php?route=purchase
###### Handla med metoden POST
###### [[2,2]]
 
 ###### RestMan is good for testing...
 
###### with_test_cases.php?test



# php-json-test
# I will keep this  exercise private and will remove it after the task is done!

In this exercise we would like you to pretend this is a real life, professional (even
though it’s not), assignment so that we can review your skills.  
After you have completed one or more of the below objectives, please send us the
result back (within 72 hours).  
   Hint: Deadlines are important to us!
   Hint: We have powerful (but sometimes stupid) anti-spam and virus filters.
   Hint: We like GitHub and other such places but we don’t want your code to be
shared with other potential candidates.  
Assignment Description  
   The task is to build a backend purchase service that fills up stock when needed. Thus
your services will, receive a purchase order and from a set of rules, restock articles
into our warehouse.  
Since this service should be triggered by a purchase on a web site, you will have to
write some code to mock the actual purchasing.
# 
#  Pseudo code for the assignment:
 resetStock(initialStock);
 forEachOrder
 purchase(order);
 refillStock();
 finish();
/* This represents setting the current level of the warehouse */
/* This is the purchase on the web shop */
/* This is the logic to refill the warehouse */
/* This represents the end of the service when stopped */
Hint: We like priorities and structure.
Must-have requirement
- A web-based service (thus headless - without UI)
- Can accept input data (simulated events on the web shop) as json or xml
- Output to a log file or screen, it should be possible to follow the events
manually while reading the output during or after all orders are executed
- The logic part of the code written in PHP (any version)
- An event (purchase) that cannot be made according to available stock must be
completely void/rejected, no partial orders allowed
- Fill up stock according to these rules
o Article 1 – order 10 more when stock is empty
o Article 2 – order 3 new only when Article 1 has less than 10 in stock
o Article 3 – order 20 more when stock is below 20
o Article 4 – order more for every order, same amount that was
purchased
- Note: Both fill up stock orders and purchase order can be assumed to take
effect immediately. Thus, there is no time between an order and deliver etc.Nice-to-have requirements
- Object oriented design
- Configurable fill up stock rules
- HTTP based service with REST based interfaces
- Start, stop and reset functions
- A quality approach to developing software (test cases, test results etc)
Think about
- Readable code
Rules
- We have not made all decisions for you, you need to make some on your own
- Any library can be used
- You can change the json file
- You can redesign the logic
- You do not have to show that you understand how to build and deploy
software, but if you do, we’re into automation of all kind
Hint: We are very flexible, but we like to know how the world works before we
review.
 
 
