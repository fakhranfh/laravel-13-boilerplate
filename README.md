# Fansafin

Fansafin is a personal finance tracker that leverages WhatsApp as its primary interface. It eliminates the friction of traditional finance apps by allowing you to log your income and expenses simply by sending a chat message.

## Key Features

* **WhatsApp-based Input:** Log transactions as easily as texting a friend.
* **Real-time Processing:** Transactions are parsed and saved instantly upon receiving your message.
* **Minimalist & Fast:** Designed for speed, removing the clutter of complex user interfaces.
* **Clean Architecture:** Built using SOLID principles and the Repository Pattern for maintainable and scalable code.

## Tech Stack

* **Framework:** Laravel
* **Integration:** WhatsApp API
* **Database:** MySQL

## How It Works

1. **Send a Message:** Send a transaction message to your Fansafin WhatsApp bot (e.g., `lunch 25000`).
2. **Auto-log:** The system parses your message and records it into the database automatically.
3. **Monitor:** Access your financial summaries through the integrated dashboard.

## Installation

1. Clone this repository:
   ```bash
   git clone [https://github.com/fakhranfh/fansafin.git](https://github.com/username/fansafin.git)