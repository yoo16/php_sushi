"use strict";

const TAX_RATE = 1.1; // 税率10%
const orders = {};
const categoryTabs = document.getElementById("category-tabs");
const menuArea = document.getElementById("menu-area");
const orderList = document.getElementById("order-list");
const totalDisplay = document.getElementById("total");
const modal = document.getElementById("modal");
const modalContent = document.getElementById("modal-content");
const modalOverlay = document.getElementById("modal-overlay");

let products = [];
let categories = [];
let currentCategory = "";

async function loadMenu() {
    try {
        const res = await fetch("api/products/get.php");
        const data = await res.json();

        products = data.products;
        console.log("products", products);

        categories = data.categories;

        currentCategory = categories[0];
        console.log("currentCategory", currentCategory);

        renderCategoryTabs(categories);
        renderMenu(currentCategory);
    } catch (err) {
        console.error("メニューの読み込み失敗", err);
    }
}

function renderCategoryTabs(categories) {
    categoryTabs.innerHTML = "";

    categories.forEach(category => {
        const btn = document.createElement("button");
        btn.textContent = category.name;
        btn.className = `flex-1 text-center px-4 py-3 border 
        ${category.id === currentCategory.id ? "bg-sky-600 text-white" : "bg-white hover:bg-sky-100"}`;

        btn.onclick = () => {
            currentCategory = category;
            renderCategoryTabs(categories);
            renderMenu(category);
        };
        categoryTabs.appendChild(btn);
    });
}

function priceWithTax(price) {
    return Math.round(price * TAX_RATE);
}

function renderMenu(category) {
    console.log("renderMenu", category);
    menuArea.innerHTML = "";

    const items = products.filter(item => item.category_id === category.id);
    const grid = document.createElement("div");
    grid.className = "grid grid-cols-1 sm:grid-cols-3 gap-1";

    items.forEach(product => {
        console.log("product", product);
        const card = document.createElement("div");
        card.innerHTML = `
        <div class="bg-white rounded shadow p-4 flex flex-col items-center cursor-pointer hover:shadow-lg transition-all" 
            onclick='openModal(${product.id})'>
            <img src="${product.image_path}" alt="${product.name}" class="w-32 rounded mb-2">
            <h3 class="text-lg font-semibold">${product.name}</h3>
            <p class="text-lg text-gray-600 mb-2">
            ${product.price}円
            (税込${priceWithTax(product.price)}円)
            </p>
            <button class="w-full bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded">注文</button>
        </div>
        `;
        grid.appendChild(card);
    });

    menuArea.appendChild(grid);
}

function openModal(productId) {
    const product = products.find(item => item.id === productId);
    modalContent.innerHTML = `
        <div class="p-4 text-2xl">
            <h3 class="font-bold text-center">${product.name}</h3>
            <img src="${product.image_path}" alt="${product.name}" class="w-32 mx-auto rounded m-6">
            <p class="text-center text-gray-600 mb-4">¥${product.price}</p>
            <div class="flex justify-center items-center gap-4 m-6">
                <button class="bg-sky-500 text-white px-4 py-2 rounded" onclick="updateQuantity(-1)">-</button>
                <span id="quantity" class="p-4">1</span>
                <button class="bg-sky-500 text-white px-4 py-2 rounded" onclick="updateQuantity(1)">+</button>
            </div>
            <div class="flex justify-between items-center gap-4">
                <button class="w-1/2 bg-sky-500 hover:bg-sky-600 text-white px-5 py-3 rounded" onclick='confirmOrder()'>注文確定</button>
                <a class="text-center w-1/2 border border-sky-500 text-sky-500 px-5 py-3 rounded cursor-pointer" onclick='closeModal()'>もどる</a>
            </div>
        </div>
    `;
    modal.classList.remove("hidden");
    modal.dataset.quantity = "1";
    modal.dataset.productId = productId;
}

function closeModal() {
    modal.classList.add("hidden");
    modalContent.innerHTML = "";
    modal.dataset.quantity = "1";
}

function updateQuantity(change) {
    const quantitySpan = document.getElementById("quantity");
    let current = parseInt(quantitySpan.textContent);
    current = Math.max(1, current + change);
    quantitySpan.textContent = current;
    modal.dataset.quantity = current;
}

async function confirmOrder() {
    const productId = parseInt(modal.dataset.productId);
    const product = products.find(item => item.id === productId);

    const visit_id = document.getElementById("visit").dataset.id;
    const quantity = parseInt(modal.dataset.quantity);
    const price = product.price;

    if (!orders[product.id]) {
        orders[product.id] = { 
            name: product.name, 
            image: product.image_path, 
            price: price, 
            count: 0 
        };
    }
    orders[product.id].count += quantity;

    const data = {
        visit_id,
        product_id: product.id,
        quantity,
        price,
    };
    try {
        const res = await fetch("api/order/add.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });
        const resData = await res.json();
        console.log("注文データ", data);
        console.log("注文結果", resData);
    } catch (err) {
        console.error("注文の送信失敗", err);
        alert("注文の送信に失敗しました。もう一度お試しください。");
        return;
    }

    closeModal();
    renderOrder();
}

function renderOrder() {
    orderList.innerHTML = "";
    let total = 0;

    for (const id in orders) {
        const product = orders[id];
        total += product.price * product.count;

        const li = document.createElement("li");
        li.innerHTML = `
        <div class="flex justify-start items-center mb-2">
            <img src="${product.image}" alt="${product.name}" class="w-16 m-2">
            <p>${product.name}</p>
            <span class="ml-auto">${product.count}</span>
        </div>
        `;
        orderList.appendChild(li);
    }

    const totalWithTax = Math.round(total * TAX_RATE);
    totalDisplay.textContent = `合計：${total}円（税込${totalWithTax}円）`;
}

loadMenu();

