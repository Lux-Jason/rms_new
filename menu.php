<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qiao's Handmade Menu</title>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<!-- Header section remains the same -->

<div class="selector-outside">
    <!-- Selector container remains the same -->
</div>

<!-- Login Modal remains the same -->

<!-- Fetch and display dishes from the database -->
<?php
include 'connectdb.php';
$dishesPerPage = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $dishesPerPage;

$filters = array();
$params = array();

// Handle search keyword
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $filters[] = "dish_name LIKE :keyword OR description LIKE :keyword";
    $params[':keyword'] = '%' . $_GET['keyword'] . '%';
}

// Handle notes filter
if (isset($_GET['notes']) && $_GET['notes'] != '') {
    $filters[] = "note = :note";
    $params[':note'] = $_GET['notes'];
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

<header>
    <div class="logo-container">
        <div style="width: 20px;"></div>
        <a href="index.php"><img src="qiao_logo.svg" alt="Qiao's Handmade" class="logo"  oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()></a>
        <div style="width: 20px;"></div>
        <nav>
            <a href="index.php" class="button">Home</a>
            <a href="menu.php" class="button" style="color: #007bff; background-color: white;">All Dishes</a>
        </nav>
    </div>
    <div>
        <button class="button login-btn" onclick="loginNotice()">Login</button>
        <button class="button register-btn" onclick="location.href=('register.php')">register</button>
        <button class="button logout-btn" onclick="logout()">Logout</button>
    </div>
</header>

<div class="reserved_area" style="height: 32px;"></div>

<div class="selector-outside">
    <div class="selector-container">
        <div class="dropdown">
            <button class="dropbtn">Notes&#8192;<span class="triangle-arrow">&#9652;</span>&#8192;</button>
            <div class="dropdown-content">
                <a href="#" data-category="notes" data-value="">All</a>
                <a href="#" data-category="notes" data-value="r">recommended dishes</a>
                <a href="#" data-category="notes" data-value="h">highly preferred dishes</a>
                <a href="#" data-category="notes" data-value="s">specially selected dishes</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">Price&#8192;<span class="triangle-arrow">&#9652;</span>&#8192;</button>
            <div class="dropdown-content">
                <a href="#" data-category="price" data-value="l10">less than $10</a>
                <a href="#" data-category="price" data-value="1020">$10-$20</a>
                <a href="#" data-category="price" data-value="2030">$20-$30</a>
                <a href="#" data-category="price" data-value="3040">$30-$40</a>
                <a href="#" data-category="price" data-value="4050">$40-$50</a>
                <a href="#" data-category="price" data-value="m50">more than $50</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">Content&#8192;<span class="triangle-arrow">&#9652;</span>&#8192;</button>
            <div class="dropdown-content">
                <a href="#" data-category="content" data-value="">All</a>
                <a href="#" data-category="content" data-value="l100">less than 100g</a>
                <a href="#" data-category="content" data-value="100200">100g to 200g</a>
                <a href="#" data-category="content" data-value="200500">200g to 500g</a>
                <a href="#" data-category="content" data-value="m500">more than 500g</a>
                <a href="#" data-category="content" data-value="other">others</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">Dish Type&#8192;<span class="triangle-arrow">&#9652;</span>&#8192;</button>
            <div class="dropdown-content">
                <a href="#" data-category="type" data-value="cf">Chinese Food</a>
                <a href="#" data-category="type" data-value="wf">Western Food</a>
                <a href="#" data-category="type" data-value="if">Indian Food</a>
                <a href="#" data-category="type" data-value="other">Other types</a>
            </div>
        </div>

        <div class="search-container-stock">
            <div class="keyword-search">
                <input type="text" id="keyword" placeholder="Search Keywords...">
                <button id="search-btn">Search</button>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeLoginModal()">&times;</span>
        <h2>Login</h2>
        <form action="#" method="post">
            <input type="text" name="username" placeholder="User Name" id="username" onfocus="inputFocus(this)" required>
            <input type="password" name="password" placeholder="Password" id="password" onfocus="inputFocus(this)" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="#">Forget password? </a></p>
        <p>Haven't got an account? <a href="#">register</a></p>
    </div>
</div>

<!-- Recommended Dishes -->
<section class="recommended-dishes" style="margin-top: 0;">
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

<!-- Page Navigation -->
<section class="page-navigation">
    <div class="pagination">
        <button class="page-number prev" onclick="changePage(-1)">&laquo;</button>
        <button class="page-number current-page" id="currentPage">1</button>
        <button class="page-number next" onclick="changePage(1)">&raquo;</button>
    </div>
</section>
<div class="reserved_area" style="height: 32px;"></div>
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

<script>
    let currentPage = 1;
    let totalPages = <?php echo $totalPages; ?>;

    // Function to change the page based on direction (prev/next)
    function changePage(direction) {
        currentPage += direction;
        if (currentPage < 1) {
            currentPage = 1;
        } else if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        document.getElementById("currentPage").textContent = currentPage;
        loadDishesForPage(currentPage);
    }

    // Function to load dishes based on the current page
    function loadDishesForPage(pageNumber) {
        const dishesContainer = document.querySelector('.dishes-container');
        const params = new URLSearchParams({
            page: pageNumber,
            // Add any other parameters (like filters) as needed
        });

        fetch('get_dishes.php?' + params.toString())
            .then(response => response.json())
            .then(data => {
                dishesContainer.innerHTML = ''; // Clear current dishes

                // Append the fetched dishes to the container
                data.dishes.forEach(dish => {
                    const dishElement = document.createElement('div');
                    dishElement.className = 'dish';
                    dishElement.innerHTML = `
                        <img src="${dish.image}" alt="${dish.dish_name}" />
                        <div class="dish-info">
                            <div class="name">${dish.dish_name}</div>
                            <div class="price">$${dish.price}</div>
                            <button class="add-to-cart-button" data-id="${dish.id}" data-name="${dish.dish_name}" data-price="${dish.price}" data-image="${dish.image}">Add to Cart</button>
                        </div>
                    `;
                    dishesContainer.appendChild(dishElement);
                });

                // Attach event listeners to "Add to Cart" buttons
                attachAddToCartListeners();
            })
            .catch(error => console.error('Error loading dishes:', error));
    }

    // Attach event listeners to all "Add to Cart" buttons
    function attachAddToCartListeners() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart-button');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const dishId = event.target.dataset.id;
                const dishName = event.target.dataset.name;
                const dishPrice = event.target.dataset.price;
                const dishImage = event.target.dataset.image;

                addToCart({ id: dishId, name: dishName, price: dishPrice, image: dishImage });
            });
        });
    }

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

    // Initialize the page
    loadDishesForPage(currentPage);

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