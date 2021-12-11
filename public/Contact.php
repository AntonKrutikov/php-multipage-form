<!doctype html>

<?php include 'Navbar.php' ?>


<div class="container">
  <style>
  	<?php include 'stylesheets/contact_page.css'; ?>
  </style>
  <form action="action_page.php">

    <label for="fname">First Name</label>
    <input type="text" id="fname" name="firstname" placeholder="Your name..">

    <label for="lname">Last Name</label>
    <input type="text" id="lname" name="lastname" placeholder="Your last name..">

    <label for="Contact Number">Contact Number</label>
    <input type="number" id="contact_no" name="contact_number" placeholder="Your contact number..">

    <label for="subject">Subject</label>
    <textarea id="subject" name="subject" placeholder="Write something.." style="height:200px"></textarea>

    <input type="submit" value="Submit">

  </form>
</div>
