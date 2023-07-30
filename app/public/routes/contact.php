<?php
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us</title>
    <link rel="stylesheet" href="./public/styles/contact.css" />
  </head>

  <body>
    <main class="contact">
      <div>
        <h1>Contact Us</h1>
        <p id="desc">Feel free to get in touch with us.</p>
      </div>
      <form>
        <div>
          <input
            id="name:feild"
            type="text"
            name="name"
            placeholder="Your Name"
            required
          />
          <p id="name:error" class="error">sflslfslfslflsflsf</p>
        </div>
        <div>
          <input
            id="email:feild"
            type="email"
            name="email"
            placeholder="Your Email"
            required
          />
          <p id="email:error" class="error"></p>
        </div>
        <div>
          <textarea
            id="message:feild"
            name="message"
            placeholder="Your Message"
            rows="15"
            required
          ></textarea>
          <p id="message:error" class="error"></p>
        </div>
        <button id="submit" type="submit">Send Message</button>
      </form>
    </main>

    <script src="./public/js/contact.js"></script>
  </body>
</html>
