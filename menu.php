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

    <!-- Header -->
    <header>
        <div class="logo-container">
            <div style="width: 20px;"></div>
            <a href="index.html"><img src="qiao_logo.svg" alt="Qiao's Handmade" class="logo"  oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()></a>
            <div style="width: 20px;"></div>
            <nav>
                <a href="index.html" class="button">Home</a>
                <a href="menu.html" class="button" style="color: #007bff; background-color: white;">All Dishes</a>
                <a href="about.html" class="button">Reserve a table</a>
            </nav>
        </div>
        <div>
            <button class="button login-btn" onclick="loginNotice()">Login</button>
            <a href="#" class="button register-btn">register</a>
        </div>
    </header>

    <div id="reserved_area" style="height: 32px;"></div>

    <div class="selector-outside">
        <div class="selector-container">
            <div class="dropdown">
                <button class="dropbtn">Notes&#8192;<span class="triangle-arrow">&#9652;</span>&#8192;</button>
                <div class="dropdown-content">
                    <a href="#" data-category="notes" data-value="">All</a>
                    <a href="#" data-category="notes" data-value="rd">recommended dishes</a>
                    <a href="#" data-category="notes" data-value="hpd">highly preferred dishes</a>
                    <a href="#" data-category="notes" data-value="ssd">specially selected dishes</a>
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
                    <a href="#" data-category="type" data-value="cd">Chinese Food</a>
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
    <section class="dishes-display">
        <div class="dishes-container" style="margin-left: 3%; margin-right: 3%;">
            <div class="dish">
                <img src="Strongly Spicy Chicken.jpg" alt="Hot Spicy Chicken" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Hot Spicy Chicken</div>
                    <div class="price">$38</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Braised Pork.jpg" alt="Braised Pork" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Braised Pork</div>
                    <div class="price">$45</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Water-Boiled Fish.jpg" alt="Water-Boiled Fish" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Water-Boiled Fish</div>
                    <div class="price">$68</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Kung Pao Shrimps.jpg" alt="Kung Pao Shrimps" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Kung Pao Shrimps</div>
                    <div class="price">$58</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Citrus Fried Beef.jpg" alt="Citrus Fried Beef" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Citrus Fried Beef</div>
                    <div class="price">$48</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Steamed Coconut Pork Grill.jpg" alt="Steamed Coconut Pork Grill" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Steamed Coconut Pork Grill</div>
                    <div class="price">$38</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Piquant Teriyaki Shrimp Curry.jpg" alt="Piquant Teriyaki Shrimp Curry" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Piquant Teriyaki Shrimp Curry</div>
                    <div class="price">$78</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Roasted Herb Vegetables Risotto.jpg" alt="Roasted Herb Vegetables Risotto" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Roasted Herb Vegetables Risotto</div>
                    <div class="price">$58</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Sautéed Sesame Fish Pizza.jpg" alt="Sautéed Sesame Fish Pizza" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Sautéed Sesame Fish Pizza</div>
                    <div class="price">$48</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Fried Herb Tofu Pizza.jpg" alt="Fried Herb Tofu Pizza" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Fried Herb Tofu Pizza</div>
                    <div class="price">$28</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Oven-baked Sesame Pork Skewer.jpg" alt="Oven-baked Sesame Pork Skewer" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Oven-baked Sesame Pork Skewer</div>
                    <div class="price">$38</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Grilled Miso Chicken Taco.jpg" alt="Grilled Miso Chicken Taco" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Grilled Miso Chicken Taco</div>
                    <div class="price">$38</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Marinated Miso Tofu Salad.jpg" alt="Marinated Miso Tofu Salad" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Marinated Miso Tofu Salad</div>
                    <div class="price">$18</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
            <div class="dish">
                <img src="Spicy Hoisin Fish Soup.jpg" alt="Spicy Hoisin Fish Soup" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
                <div class="dish-info">
                    <div class="name">Spicy Hoisin Fish Soup</div>
                    <div class="price">$48</div>
                    <button class="add-to-cart-button">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Page Navigation -->
    <section class="page-navigation">
        <div class="pagination">
            <button class="page-number prev" onclick="changePage(-1)">&laquo;</button>
            <button class="page-number current-page" id="currentPage">1</button>
            <button class="page-number next" onclick="changePage(1)">&raquo;</button>
        </div>
    </section>

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

    let currentPage = 1;
    const totalPages = 5; // let page sum be 5, this can be finalized by calculating directly

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

    // Only finished the turning pages function, but ever written the show next page's dishes function
</script>
</body>
</html>