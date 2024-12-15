<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qiao's Handmade Home</title>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<?php
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    echo "<script>alert('Welcome, $username')</script>";
    echo "<script>var isLoggedIn = true;</script>";
} else {
    echo "<script>var isLoggedIn = false;</script>";
}
?>
<!-- Header -->
<header>
    <div class="logo-container">
        <div style="width: 20px;"></div>
        <a href="index.php"><img src="qiao_logo.svg" alt="Qiao's Handmade" class="logo"  oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()></a>
        <div style="width: 20px;"></div>
        <nav>
            <a href="index.php" class="button" style="color: #007bff; background-color: white;">Home</a>
            <a href="menu.php" class="button">All Dishes</a>
        </nav>
    </div>
    <div>
        <button class="button login-btn" onclick="loginNotice()">Login</button>
        <button class="button register-btn" onclick="location.href=('register.php')">register</button>
        <button class="button logout-btn" onclick="logout()">Logout</button>
    </div>
</header>

<div id="reserved_area" style="height: 32px;"></div>


<div class="content">
    <div id="mp_functions">

        <div class="type-container_mp" style="border-radius: 24px;">
            <!-- Here is a tab area, four options in order from the top to the bottom of the row, used to display the distribution of the meal, the tab is clicked to jump to the Type page and filtering operations, the left side of the tab for the product image, the right side of the text description -->
            <div class="type_mp_single" onclick="location.href=('#')">
                <img src="yuecai.jpg" alt="yuecai" style="height: 92.5px; border-radius: 24px; margin-right: 5px;">
                <div class="type_mp_single_text">
                    <p class="type_name">Canton Food</p>
                    <p class="type_description">Cantonese cuisine emphasizes freshness and delicate flavors. </p>
                </div>
            </div>
            <div class="type_mp_single" onclick="location.href=('#')">
                <img src="xiangcai.jpg" alt="xiangcai" style="width: 92.5px; border-radius: 24px; margin-right: 5px;">
                <div class="type_mp_single_text">
                    <p class="type_name">Hunan Food</p>
                    <p class="type_description">Hunan cuisine is known for its bold and spicy flavors. </p>
                </div>
            </div>
            <div class="type_mp_single" onclick="location.href=('#')">
                <img src="chuancai.jpg" alt="Image3" style="width: 92.5px; border-radius: 24px; margin-right: 5px;">
                <div class="type_mp_single_text">
                    <p class="type_name">Sichuan Food</p>
                    <p class="type_description">Sichuan cuisine is famous its numbing spiciness and rich flavor. </p>
                </div>
            </div>
            <div class="type_mp_single" onclick="location.href=('#')">
                <img src="worldfood.jpg" alt="Image4" style="width: 92.5px; border-radius: 24px; margin-right: 5px;">
                <div class="type_mp_single_text">
                    <p class="type_name">Foreign Food</p>
                    <p class="type_description">We also serve food from all over the world. You can find your home here. </p>
                </div>
            </div>
        </div>

        <!-- Slideshow -->
        <div class="slideshow-container" style="border-radius: 24px">
            <div class="mySlides fade">
                <img src="Qiao's Handmade pst1.png" style="width:100%; border-radius: 24px; height: 400px;"  oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
            </div>
            <div class="mySlides fade">
                <img src="Qiao's Handmade pst2.png" style="width:100%; border-radius: 24px; height: 400px;"  oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
            </div>
            <div class="mySlides fade">
                <img src="Qiao's Handmade pst3.png" style="width:100%; border-radius: 24px; height: 400px;"  oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
            </div>
        </div>

        <div class="personal_model" style="border-radius: 24px;">
            <!-- Here is a personal-related functional areas, the upper half of the login-related functions and personal account-related information (this module can be expressed in div, do not have to write the function), the lower half is divided into the favourites, the order history of the functionality of the portal -->
            <div class="personal_model_top">
                <!-- You shall logout then login to assure the correctly performance of the program. -->
                <?php
                if (isset($_SESSION["username"])) {
                    $username = $_SESSION["username"];
                    $type = $_SESSION['type'];
                    echo '<!-- Account Functions -->
                    <div style="width: 100%; height: 15px;"></div>
                    <div style="display: flex; width: 100%; align-items: center; justify-content: center;">
                        <div style="border: 0.5px #33333330 solid; width: 60px; height: 60px; border-radius: 30px; overflow: hidden;"><img src="profile_photo.jpeg" width="60px" height="60px"></div>
                        <div>
                            <p style="font-size: 150%; font-weight: bolder; margin: 0; margin-left: 5px;">Good afternoon</p>
                            <p style="margin: 0; margin-left: 5px;">'.$username.'</p>
                        </div>
                    </div>
                    <!-- Account Information -->
                    <!-- reserved area -->
                    <div style="width: 100%;" style="display: flex; align-items: center; justify-content: center; text-align: center;">
                        <p style="text-align: center; font-size: 120%; font-weight: bolder; margin-bottom: 3px; color: #333;">You are '.$type.' user. </p>
                        <p style="text-align: center; margin-top: 3px; color: #888;">You can experience our excellent dining experience. </p>
                    </div>
                    <button class="button logout-btn" onclick="logout()" style="width: 100%; font-size: 120%; font-weight: bolder; display: block; ">Logout</button>';
                } else {
                    echo '<!-- Account Functions -->
                    <div style="width: 100%; height: 15px;"></div>
                    <div style="display: flex; width: 100%; align-items: center; justify-content: center;">
                        <div style="border: 0.5px #33333330 solid; width: 60px; height: 60px; border-radius: 30px; overflow: hidden;"><img src="profile_photo.jpeg" width="60px" height="60px"></div>
                        <div>
                            <p style="font-size: 150%; font-weight: bolder; margin: 0; margin-left: 5px;">Good afternoon</p>
                            <p style="margin: 0; margin-left: 5px;"><a href="register.php" style="text-decoration: none; color: #888;">Register</a></p>
                        </div>
                    </div>
                    <!-- Account Information -->
                    <!-- reserved area -->
                    <div style="width: 100%;" style="display: flex; align-items: center; justify-content: center; text-align: center;">
                        <p style="text-align: center; font-size: 120%; font-weight: bolder; margin-bottom: 3px; color: #333;">Login to experience more. </p>
                        <p style="text-align: center; margin-top: 3px; color: #888;">You can use your VIP after logged in. </p>
                    </div>
                    <button class="button login-btn" onclick="loginNotice()" style="width: 100%; font-size: 120%; font-weight: bolder;">Login</button>';
                } ?>
            </div>
            <div style="width: 100%; height: 12px;"></div>
            <div class="account-balance_mp">
                <p style="text-align: center; font-size: 108%; font-weight: bold; margin-top: 3px;">Account Balance</p>
                <p style="text-align: center; font-size: 200%; font-weight: lighter; margin-top: 8px; color: #AAA; margin-bottom: 12px;"><?php if (isset($_SESSION["username"])) {$remains = $_SESSION['remains']; echo '$'.$remains; } else {echo '$88.88';} ?></p>
            </div>
            <div class="personal_model_bottom" style="display: flex; margin: 3px; border-radius: 24px;">
                <!-- To be laid in the middle! -->
                <div class="personal-functions-buttons_mp">
                    <div id="icon-personal-functions_mp" style="width: 100%; align-items: center; justify-content: center;">
                        <img src="collection.svg" style="width: 40px;  ">
                    </div>
                    <p>My Collections</p>
                </div>
                <div class="personal-functions-buttons_mp">
                    <div id="icon-personal-functions_mp" style="width: 100%; align-items: center; justify-content: center;">
                        <img src="order_his.svg" style="width: 40px; ">
                    </div>
                    <p>My Orders</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLoginModal()">&times;</span>
            <h2>Login</h2>
            <form action="logincheck_users.php" method="post"> <!-- Change action to login.php -->
                <input type="text" name="username" placeholder="User Name" id="username" onfocus="inputFocus(this)" required>
                <input type="password" name="password" placeholder="Password" id="password" onfocus="inputFocus(this)" required>
                <button type="submit" onclick="location.href=('logincheck_users.php')">Login</button>
            </form>
            <p><a href="#">Forget password? </a></p>
            <p>Haven't got an account? <a href="register.php">register</a></p>
        </div>
    </div>

    <?php
    include 'connectdb.php';
    $dishesPerPage = 12;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $dishesPerPage;

    $filters = array();
    $params = array();

    // Always add the note filter for "r"
    $filters[] = "note = :note";
    $params[':note'] = 'r';

    // Handle search keyword
    if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
        $filters[] = "dish_name LIKE :keyword OR description LIKE :keyword";
        $params[':keyword'] = '%' . $_GET['keyword'] . '%';
    }

    // Handle notes filter
    if (isset($_GET['notes']) && $_GET['notes'] != '') {
        $filters[] = "note = :notes";
        $params[':notes'] = $_GET['notes'];
    }

    // Handle price filter
    if (isset($_GET['price']) && $_GET['price'] != '') {
        switch ($_GET['price']) {
            case 'l10':
                $filters[] = "price < 10";
                break;
            case '1020':
                $filters[] = "price BETWEEN 10 AND 20";
                break;
            // Add cases for other price ranges
        }
    }

    // Handle content filter and dish type filter similarly

    // Construct the SQL query
    $sql = "SELECT * FROM menu";
    if (!empty($filters)) {
        $sql .= " WHERE " . implode(" AND ", $filters);
    }
    $sql .= " LIMIT :offset, :limit";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $dishesPerPage, PDO::PARAM_INT);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch total number of dishes
    $totalSql = "SELECT COUNT(*) AS total FROM menu";
    if (!empty($filters)) {
        $totalSql .= " WHERE " . implode(" AND ", $filters);
    }
    $totalStmt = $conn->prepare($totalSql);
    foreach ($params as $key => $value) {
        $totalStmt->bindValue($key, $value);
    }
    $totalStmt->execute();
    $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($total / $dishesPerPage);
    ?>

    <!-- Recommended Dishes -->
    <section class="recommended-dishes" style="margin-top: 0;">
        <h2>Recommended Dishes</h2>
        <section class="dishes-display">
            <div class="dishes-container" style="margin-left: 3%; margin-right: 3%;">
                <?php foreach ($dishes as $dish): ?>
                    <div class="dish">
                        <!-- Convert image from BLOB to Base64 and embed in img src -->
                        <?php if ($dish['image']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($dish['image']); ?>"
                                 alt="<?php echo htmlspecialchars($dish['dish_name']); ?>"
                                 loading="lazy"
                                 oncontextmenu="return false"
                                 onselectstart="return false"
                                 ondragstart="return false"
                                 onbeforecopy="return false"
                                 oncopy="document.selection.empty()"
                                 onselect="document.selection.empty()">
                        <?php else: ?>
                            <!-- Placeholder if no image is available -->
                            <img src="./nodish.jpg"
                                 alt="No Image Available"
                                 loading="lazy"
                                 oncontextmenu="return false"
                                 onselectstart="return false"
                                 ondragstart="return false"
                                 onbeforecopy="return false"
                                 oncopy="document.selection.empty()"
                                 onselect="document.selection.empty()">
                        <?php endif; ?>
                        <div class="dish-info">
                            <div class="name"><?php echo $dish['dish_name']; ?></div>
                            <div class="price"><?php echo '$' . $dish['price']; ?></div>
                            <button class="add-to-cart-button" id="add-to-cart_<?php echo $dish['dish_id']; ?>">Add to Cart</button>
                        </div>
                        <input type="text" class="hidden-dishid" id="<?php echo $dish['dish_id']; ?>" value="<?php echo $dish['dish_id']; ?>" readonly/>
                        <!-- Hidden form to store the data of the item -->
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </section>

    <!-- Cart and float experience -->

    <div id="cart-float" onclick="openCart()" >
        🛒
    </div>

    <!-- Back to top button -->
    <button id="back-to-top" style="display: none;">&uarr;</button>

    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCartModal()">&times;</span>
            <h2>My Cart</h2>
            <div class="cart-items" id="cartItems">
                <!-- Cart items will be dynamically inserted here -->
            </div>
            <div class="cart-summary">
                <div class="total-quantity">Total Quantity: <span id="totalQuantity">0</span>&emsp;Total Price: $<span id="totalPrice">0.00</span></div>
                <div><button id="checkout_button" style="margin-top: 12px;">Check Out</button></div>
            </div>
        </div>
    </div>

</div>

<!-- Footer -->
<footer>
    <!-- Other Information -->
    <section class="other-information" style="color: #000000;">
        <h2>About us</h2>
        <div class="info-container">
            <div class="info-box">
                <h3>Running Time: </h3>
                <p>Monday to Saturday<br>10:00 - 22:00</p>
            </div>
            <div class="info-box">
                <h3>Contact us: </h3>
                <p>Telephone: 19935820001</p>
                <p>E-mail: info@qiaohandmade.com</p>
            </div>
            <div class="info-box">
                <h3>Address: </h3>
                <p>BNU-UIC 2nd D5B-A111</p>
            </div>
        </div>
    </section>
    <div class="footer-content">
        <p>&copy; 2024 Qiao's Handmade. All rights reserved</p>
        <a href="admin.php">Admin Page</a>
    </div>
</footer>

<script>

    let cart = [];
    let isMember = false; // Default: not a member

    function openCart() {
        document.getElementById("cartModal").style.display = "block";
    }

    function closeCartModal() {
        document.getElementById("cartModal").style.display = "none";
    }

    // Function to load cart items from the database
    function loadCart() {
        fetch('get_cart_items.php') // Replace with the actual backend endpoint
            .then(response => response.json())
            .then(data => {
                cart = data.items; // Store the cart items
                isMember = data.isMember; // Store the user's membership status
                updateCartDisplay();
            })
            .catch(error => console.error('Error fetching cart data:', error));
    }

    // Function to update cart display
    function updateCartDisplay() {
        const cartItemsContainer = document.getElementById('cartItems');
        cartItemsContainer.innerHTML = '';  // Clear current items

        let totalQuantity = 0;
        let totalPrice = 0;

        cart.forEach(item => {
            const discount = isMember ? 0.1 : 0; // 10% discount for members
            const priceAfterDiscount = item.price * (1 - discount);
            const totalItemPrice = priceAfterDiscount * item.quantity;

            totalQuantity += item.quantity;
            totalPrice += totalItemPrice;

            const itemElement = document.createElement('div');
            itemElement.classList.add('cart-item');

            itemElement.innerHTML = `
        <div class="item-image">
            <img src="${item.image}" alt="${item.dish_name}">
        </div>
        <div class="item-details">
            <div class="item-name">${item.dish_name}</div>
            <div class="item-info">Discount: ${discount * 100}%&emsp;Quantity: ${item.quantity}&emsp;Price: $${priceAfterDiscount.toFixed(2)}</div>
            <div class="item-total">Total Price: $${totalItemPrice.toFixed(2)}</div>
            <button onclick="updateItemQuantity(${item.dish_id}, 'decrease')">-</button>
            <button onclick="updateItemQuantity(${item.dish_id}, 'increase')">+</button>
        </div>
        `;

            cartItemsContainer.appendChild(itemElement);
        });

        document.getElementById('totalQuantity').innerText = totalQuantity;
        document.getElementById('totalPrice').innerText = totalPrice.toFixed(2);
    }

    // Function to update quantity of an item
    function updateItemQuantity(dishId, action) {
        const item = cart.find(item => item.dish_id === dishId);
        if (!item) return;

        if (action === 'increase') {
            item.quantity++;
        } else if (action === 'decrease' && item.quantity > 1) {
            item.quantity--;
        }

        // Update the cart in the backend
        fetch('update_cart.php', { // Replace with the actual backend endpoint
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ dishId: dishId, quantity: item.quantity })
        })
            .then(response => response.json())
            .then(data => {
                loadCart(); // Reload the cart to reflect changes
            })
            .catch(error => console.error('Error updating cart:', error));
    }

    // Function to add a dish to the cart
    function addToCart(dishId, dishName, price, image) {
        // Check if the item is already in the cart
        const existingItem = cart.find(item => item.dish_id === dishId);
        if (existingItem) {
            // Increase quantity if item exists
            existingItem.quantity++;
        } else {
            // Add new item to cart
            const newItem = {
                dish_id: dishId,
                dish_name: dishName,
                price: price,
                quantity: 1,
                image: image
            };
            cart.push(newItem);
        }

        // Update cart in backend
        fetch('add_to_cart.php', {  // Backend endpoint to add an item to the cart
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ dishId: dishId, quantity: 1 })  // Send quantity as 1
        })
            .then(response => response.json())
            .then(data => {
                loadCart(); // Reload the cart to reflect the added item
            })
            .catch(error => console.error('Error adding item to cart:', error));
    }

    // Event listener for "Add to Cart" buttons
    /* document.querySelectorAll('.add-to-cart-button').forEach(button => {
        button.addEventListener('click', function() {
            const dishElement = this.closest('.dish');
            const dishId = parseInt(dishElement.querySelector('.dish-info').dataset.dishId);
            const dishName = dishElement.querySelector('.name').textContent;
            const price = parseFloat(dishElement.querySelector('.price').textContent.slice(1));  // Remove "$" sign and parse as float
            const image = dishElement.querySelector('img').src;

            addToCart(dishId, dishName, price, image);
        });
    });
    */

    // Load cart when page loads
    document.addEventListener('DOMContentLoaded', () => {
        loadCart();
    });

    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {slideIndex = 1}
        slides[slideIndex-1].style.display = "block";
        setTimeout(showSlides, 3000); // Change image every 3 seconds
    }

    function loginNotice() {
        document.getElementById("loginModal").style.display = "block";
    }

    function closeLoginModal() {
        document.getElementById("loginModal").style.display = "none";
    }

    function inputFocus(input) {
        document.getElementById("username").style.backgroundColor = "white";
        document.getElementById("password").style.backgroundColor = "white";
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        const backToTopButton = document.getElementById('back-to-top');

        // event listener to listen to scroll
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        };

        // click back to top button
        backToTopButton.onclick = function() {
            smoothScrollToTop();
        }

        // scroll to the top smoothly
        function smoothScrollToTop() {
            const c = document.documentElement.scrollTop || document.body.scrollTop;
            if (c > 0) {
                window.requestAnimationFrame(smoothScrollToTop);
                window.scrollTo(0, c - c / 10);
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        if (isLoggedIn) {
            document.querySelector('.logout-btn').style.display = 'block';
            document.querySelector('.login-btn').style.display = 'none';
            document.querySelector('.register-btn').style.display = 'none';
        } else {
            document.querySelector('.logout-btn').style.display = 'none';
        }
    });

    function logout() {
        window.location.href = 'logout.php';
    }

    // To realize the place-an-order function (frmo add to cart to check out)
    // get button-id the customer has pressed
    // const buttonId = button.id;
    // the format of the buttons' id: "add-to-cart_[dish_id]"
    // extract dish_id
    // const dishId = buttonId.split('_')[1];

    document.querySelectorAll('.add-to-cart-button').forEach(button => {
        button.addEventListener('click', function() {
            const dishId = this.id.split('_')[1];
            // console.log(dishId);
            // Using AJAX Requests to send dishId to PHP Scripts
            fetch('add_cart_s-information.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ dish_id: dishId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                    } else {
                        const dishName = data.dish_name;
                        const dishPrice = data.price;
                        // test shopping cart
                        console.log(`Dish added: ${dishName}, Price: ${dishPrice}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });

</script>

</body>
</html>