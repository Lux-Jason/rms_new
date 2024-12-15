<!DOCTYPE html>
<!-- Full website is under construction. -->

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwapSpot Admin Add Item</title>
    <link rel="icon" href="SwapSpot_logo_small.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-image: url('bg_07_16.jpg');
        background-size: 100%; 
        background-position: center;
        background-repeat: no-repeat; /* repeat pic */
        height: 100vh; /* fill the page */
    }
    .main-container {
        display: flex;
        position: relative;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .add_item_admin-container {
        width: 30%;
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
    }
    .add_item_admin-container h2 {
        margin-top: 0;
        text-align: center;
    }
    .add_item_admin-container form {
        text-align: center;
    }
    .add_item_admin-container input[type="text"],
    .add_item_admin-container input[type="number"],
    .add_item_admin-container button {
        width: 100%;
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    .add_item_admin-container button {
        background-color: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
    }
    .links {
        color: #00aeec;
    }
    .links:hover {
        color: #00aeec;
    }
    .links:visited {
        color: #00aeec;
    }
</style>
</head>
<body>
<div class="main-container">
    <div class="add_item_admin-container">
        <img src="SwapSpot_logo.svg" style="width: 300px;">
        <h1 style="font-family: 'Segoe UI Light', Arial, sans-serif; padding: 0px; margin: 0px; color: #aaa;" id="greetings"></h1>
        <h2 style="margin: 20px;">Admin Add Item</h2>
        <form action="additem_sql_op.php" method="post">
            <input type="text" name="itemname" placeholder="Input Item Name" id="itemname" onfocus="inputFocus(this)" required>
            <input type="number" name="inventory" placeholder="Input the number we have" id="inventory" onfocus="inputFocus(this)" required>
            <input type="number" name="selling_price" placeholder="Input the dish price" id="selling_price" onfocus="inputFocus(this)" required>
            <input type="text" name="image_path" placeholder="Input Image Path" id="image_path" onfocus="inputfocus(this)" value="./" required>
            <textarea type="text" name="ingredient" placeholder="Input Ingredient" id="ingredient" onfocus="inputfocus(this)" required>
            <textarea type="text" name="description" placeholder="Input Description" id="description" onfocus="inputfocus(this)" required>
            <button type="submit">Add</button>
        </form>
        <p>Back to <a href="Admin_pg.php" class="links">Admin Page</a></p>
        <footer>&copy;Copyright 2024 Qiao's Handmade. All rights reserved.</footer>
    </div>
</div>

<script>
    var currentDate = new Date();
    var currentHour = currentDate.getHours();
    var body = document.body;
    if (currentHour<4 || currentHour>19) {
        body.style.backgroundImage = "url('bg_19_04.jpg')";
        document.getElementById("greetings").innerHTML = "May you a good night.";
    } else if (currentHour>=4 || currentHour<7) {
        body.style.backgroundImage = "url('bg_04_07.jpg')";
        document.getElementById("greetings").innerHTML = "Good morning. <br>A new day is about to begin.";
    } else if (currentHour>16 || currentHour<=19) {
        body.style.backgroundImage = "url('bg_16_19.jpg')";
        document.getElementById("greetings").innerHTML = "The day's coming to an end. <br>Now cherish the evening sunset.";
    } else {
        body.style.backgroundImage = "url('bg_07_16.jpg')";
        document.getElementById("greetings").innerHTML = "May you have a fulfilling day.";
    }
    function inputFocus(input) {
        input.style.backgroundColor = "white";
        input.style.borderColor = "#00AEEC";
    }
</script>

</body>
</html>