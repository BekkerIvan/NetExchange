# Netexchange Practical Test for Intermediate to Senior PHP Developer Candidates

## Overview
Candidates are required to complete a practical test. This test will allow the candidate to demonstrate that he/she fully understands the concepts needed to write coherent software.

## Objective
- Using MySQL / PostgreSQL create a database that will hold all data required to be stored
- Write a web service in PHP that will do any data retrieval and processing
- Create a web application that will consume the web service and present the user with a workable user experience

## What will be assessed
- Database Structure
- Web service (API Design)
- PHP Development skills, including formatting
- Ability to write coherent documentation

## What is not required
- A 'pretty' UI, though it will need to be user friendly
- User registration and authentication

## Acceptance Criteria
A web application must be created that will allow a user to purchase foreign currencies:

- The page should display the available currencies for selection by the user
- The page should have inputs where the user can enter the amount of foreign currency they wish to purchase or the amount of ZAR currency they wish to pay
- Once the user has entered either amount and selected the foreign currency necessary calculations need to be performed that will display the amount they need to pay in ZAR
- The user can select to purchase the foreign currency, an 'order' for the currency must be saved to the database and the user must be shown a confirmation

## Details

### Currency Information
- **Payment currency:** ZAR (South African Rand)
- **Available currencies for purchase:**
    - USD (US Dollar)
    - GBP (British Pound)
    - EUR (Euro)
    - KES (Kenyan Shilling)

### Exchange Rates
- **ZAR to USD:** 0.0808279
- **ZAR to GBP:** 0.0527032
- **ZAR to EUR:** 0.0718710
- **ZAR to KES:** 7.81498

### Surcharge Rates
A surcharge must be added to orders and differs for each foreign currency:
- **USD:** 7.5%
- **GBP:** 5%
- **EUR:** 5%
- **KES:** 2.5%

### Order Information Storage
The following information must be saved with each order:
- Foreign currency purchased
- Exchange rate for the foreign currency
- Surcharge percentage
- Amount of foreign currency purchased
- Amount to be paid in ZAR
- Amount of surcharge
- Date created

### Special Actions by Currency
When an order is saved, the following extra actions need to be taken for different foreign currencies:

- **USD:** No action
- **GBP:** Send email with order details (can be basic text or HTML email to any configurable email address)
- **EUR:** Apply a 2% discount on the total order amount (this needs to be configurable for the currency and saved separately on an order. This must not be included in the final currency calculation)
- **KES:** No action

## Bonus Requirements
Use a foreign exchange API to get exchange rates. Results from the API should be cached by updating the foreign exchange rates in the database for each currency. The updating of exchange rates only needs to happen periodically in the real world, thus the update should be triggered via a URL or the command line.

**Suggested API:** You can use [jsonrates](https://jsonrates.com) to retrieve the rates. Register for a free API key.

## Development Environment
A PHP Development environment can be downloaded from [XAMPP](https://www.apachefriends.org/index.html)

---

# Setup

## Server—(Optional)
Within the `server` directory, edit the `.env` file, change the SMTP detail to fit your own sending email address.
Note, the `SMTP_PASSWORD` is only valid for 7 days, and will be removed. This will hinder emails being sent form the original email address provided for free.

## Starting project
No further setup is needed; all you need is to have Docker installed with docker-compose.
When this is done, run the command `docker-compose up -d --build` to start the application

Navigate to [localhost - client](http://localhost/) - here lies the basic client side in which you can interact and create orders.

## Database
Database details can be found in the `docker-compose.yml` file

To sync the data model, navigate to the [server url](http://localhost:8080/api/db/sync). This will sync the code to the database

Once this is done, you can now sync the [currencies](http://localhost:8080/api/db/currency) to load them into the database and create the respected controller files