<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qiao's Handmade Menu - 下单页面</title>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .cart, .order-summary {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 20px;
            width: 80%;
            margin: 0 auto;
        }
        .cart-list {
            list-style-type: none;
            padding: 0;
        }
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            border: 1px solid #e0e0e0;
            padding: 10px;
            border-radius: 5px;
            background-color: #fafafa;
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
        }
        .cart-item-details {
            flex: 1;
        }
        .remove-btn {
            background-color: #e3b873;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
        }
        .remove-btn:hover {
            background-color: #d3a863;
        }
        .order-summary {
            margin-top: 20px;
        }
        .order-item {
            margin-bottom: 10px;
        }
        .pay-btn {
            background-color: #e3b873;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        .pay-btn:hover {
            background-color: #d3a863;
        }
        .user-status {
            text-align: right;
            margin-top: 20px;
            margin-right: 3%;
        }

    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo-container">
            <div style="width: 20px;"></div>
            <a href="index.php"><img src="qiao_logo.svg" alt="Qiao's Handmade" class="logo" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()></a>
            <div style="width: 20px;"></div>
            <nav>
                <a href="index.php" class="button">Home</a>
                <a href="menu.html" class="button">All Dishes</a>
                <a href="about.html" class="button">Reserve a table</a>
            </nav>
        </div>
        <div>
            <button class="button login-btn" onclick="loginNotice()">Login</button>
            <a href="#" class="button register-btn">register</a>
        </div>
    </header>

    <div id="reserved_area" style="height: 32px;"></div>

    <div id="reserved_area" style="height: 32px;"></div>

    <!-- Shopping Cart -->
    <section class="cart">
        <h2>My Cart</h2>
        <ul class="cart-list">
            <li class="cart-item">
                <img src="Strongly Spicy Chicken.jpg" alt="Hot Spicy Chicken" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="cart-item-details">
                    <h3>Hot Spicy Chicken</h3>
                    <p>Price: : $38.00</p>
                    <p>Quantity: : 2</p>
                    <p>Total: : $76.00</p>
                </div>
                <button class="remove-btn" onclick="removeFromCart('Hot Spicy Chicken')">Remove</button>
            </li>
            <li class="cart-item">
                <img src="Braised Pork.jpg" alt="Braised Pork">
                <div class="cart-item-details">
                    <h3>Braised Pork</h3>
                    <p>Price: : $45.00</p>
                    <p>Quantity: : 1</p>
                    <p>Total: : $45.00</p>
                </div>
                <button class="remove-btn" onclick="removeFromCart('Braised Pork')">Remove</button>
            </li>
            <li class="cart-item">
                <img src="Piquant Teriyaki Shrimp Curry.jpg" alt="Piquant Teriyaki Shrimp Curry">
                <div class="cart-item-details">
                    <h3>Piquant Teriyaki Shrimp Curry</h3>
                    <p>Price: : $78.00</p>
                    <p>Quantity: : 1</p>
                    <p>Total: : $78.00</p>
                </div>
                <button class="remove-btn" onclick="removeFromCart('Piquant Teriyaki Shrimp Curry')">Remove</button>
            </li>
        </ul>
    </section>

    <!-- Order Summary -->
    <section class="order-summary">
        <h2>Order informtion</h2>
        <div class="order-item">
            <span>Order Total: : $200.00</span>
        </div>
        <div class="order-item">
            <span>deduction: 10%</span>
        </div>
        <div class="order-item">
            <span>To be paid: $180.00</span>
        </div>
        <button class="pay-btn" onclick="pay()">Pay</button>
    </section>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLoginModal()">&times;</span>
            <h2>Login</h2>
            <form action="#" method="post" onsubmit="return handleLogin()">
                <input type="text" name="username" placeholder="User Name" id="username" onfocus="inputFocus(this)" required>
                <input type="password" name="password" placeholder="Password" id="password" onfocus="inputFocus(this)" required>
                <button type="submit">Login</button>
            </form>
            <p><a href="#">Forget Password? </a></p>
            <p>Haven't got an account? <a href="#">Register</a></p>
        </div>
    </div>

    <!-- Other Information -->
    <div id="reserved_area" style="height: 32px;"></div>

    <!-- Other Information -->
    <section class="other-information">
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

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Qiao's Handmade. All rights reserved</p>
            <a href="admin.html">Admin Page</a>
        </div>
    </footer>

    <script>
        let cart = [
            { name: 'Hot Spicy Chicken', Price:  38, quantity: 2, image: 'food1.jpg' },
            { name: 'Braised Pork', Price: 45, quantity: 1, image: 'food2.jpg' },
            { name: 'Piquant Teriyaki Shrimp Curry', Price: 78, quantity: 1, image: 'food3.jpg' }
        ];

        const discount = 0.10; // VIP deduction

        function loginNotice() {
            document.getElementById("loginModal").style.display = "block";
        }

        function closeLoginModal() {
            document.getElementById("loginModal").style.display = "none";
        }

        function inputFocus(input) {
            input.style.backgroundColor = "white";
        }

        function handleLogin() {
            alert('Login Function is under construction');
            return false;
        }

        function removeFromCart(dishName) {
            cart = cart.filter(item => item.name !== dishName);
            updateCartDisplay();
            alert(dishName + ' removed from your cart');
        }

        function updateCartDisplay() {
            const cartSection = document.querySelector('.cart-list');
            cartSection.innerHTML = cart.length === 0 ? '<p>Cart is emoty. </p>' : cart.map(item => `
                <li class="cart-item">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="cart-item-details">
                        <h3>${item.name}</h3>
                        <p>Price: : $${item.Price: .toFixed(2)}</p>
                        <p>Quantity: : ${item.quantity}</p>
                        <p>Total: : $${(item.Price * item.quantity).toFixed(2)}</p>
                    </div>
                    <button class="remove-btn" onclick="removeFromCart('${item.name}')">Remove</button>
                </li>
            `).join('');

            // Recalculate total Price:  and discounted Price: 
            const totalPrice:  = cart.reduce((sum, item) => sum + item.Price:  * item.quantity, 0);
            const discountedPrice:  = totalPrice:  * (1 - discount);

            // Update order summary
            const orderSummarySection = document.querySelector('.order-summary');
            orderSummarySection.innerHTML = `
                <h2>Order Information</h2>
                <div class="order-item">
                    <span>Order Total: : $${totalPrice: .toFixed(2)}</span>
                </div>
                <div class="order-item">
                    <span>Duduction: ${discount * 100}%</span>
                </div>
                <div class="order-item">
                    <span>To be paid: $${discountedPrice: .toFixed(2)}</span>
                </div>
                <button class="pay-btn" onclick="pay()">Pay</button>
            `;
        }

        function pay() {
            const discountedPrice:  = cart.reduce((sum, item) => sum + item.Price:  * item.quantity, 0) * (1 - discount);
            alert('The actual money to be paid: $' + discountedPrice: .toFixed(2));
        }

        // initiating the cart
        updateCartDisplay();
    </script>
</body>
</html>