"use strict";

const TAX_RATE = 1.1;
const categoryTabs = document.getElementById("category-tabs");
const menuArea = document.getElementById("menu-area");
const productArea = document.getElementById("product-area");
const orderList = document.getElementById("order-list");
const totalDisplay = document.getElementById("total");
const modal = document.getElementById("modal");
const modalContent = document.getElementById("modal-content");
const modalOverlay = document.getElementById("modal-overlay");

let total = 0;
let orders = [];
let products = [];
let categories = [];
let currentCategory = "";
let visit_id = document.getElementById("visit").dataset.id;

async function fetchCategories() {
    try {
        const res = await fetch("api/category/fetch.php");
        const data = await res.json();
        return data.categories || [];
    } catch (err) {
        console.error("カテゴリデータの取得失敗", err);
        return [];
    }
}

async function fetchOrders() {
    try {
        const uri = `api/order/fetch.php?visit_id=${visit_id}`
        const res = await fetch(uri);
        const data = await res.json();

        orders = data.orders || [];

        console.log("注文の取得URI:", uri);
        console.log("取得した注文データ:", orders);
        return orders;
    } catch (err) {
        console.error("注文の取得失敗", err);
    }
}

async function fetchProducts() {
    try {
        const res = await fetch("api/product/fetch.php");
        const data = await res.json();
        return data.products || [];
    } catch (err) {
        console.error("商品データの取得失敗", err);
        return [];
    }
}

function priceWithTax(price) {
    return Math.round(price * TAX_RATE);
}

async function addOrder(order) {
    console.log("注文を追加:", order);
    // クライアント側で即時更新
    orders.push(order);

    try {
        const res = await fetch("api/order/add.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(order),
        });
        const data = await res.json();
        return data.products || [];
    } catch (err) {
        console.error("商品データの取得失敗", err);
        return [];
    }
}

async function loadProducts() {
    try {
        categories = await fetchCategories();
        // 初期カテゴリーを設定
        currentCategory = categories[0];

        // 商品データ取得
        products = await fetchProducts();

        // カテゴリータブとメニューをレンダリング
        renderCategoryTabs(categories);
        renderMenu(currentCategory);
    } catch (err) {
        console.error("メニューの読み込み失敗", err);
    }
}

async function loadOrders() {
    // オーダーデータ取得
    orders = await fetchOrders();
    // 合計金額
    total = orders.reduce((sum, order) => sum + (order.quantity * order.price), 0);
    // 注文履歴をレンダリング
    renderOrder();
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

function renderMenu(category) {
    // 商品をフィルタリング
    const filterProducts = products.filter(item => item.category_id === category.id);

    productArea.innerHTML = "";
    filterProducts.forEach(product => {
        const card = document.createElement("div");
        card.innerHTML = `
        <div class="bg-white rounded shadow p-4 flex flex-col items-center cursor-pointer hover:shadow-lg transition-all" 
            onclick='openOrder(${product.id})'>
            <img src="${product.image_path}" alt="${product.name}" class="w-32 rounded mb-2">
            <h3 class="text-lg font-semibold">${product.name}</h3>
            <p class="text-lg text-gray-600 mb-2">
            ${product.price}円
            (税込${priceWithTax(product.price)}円)
            </p>
            <button class="w-full bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded">注文</button>
        </div>
        `;
        productArea.appendChild(card);
    });
}

function openOrder(productId) {
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
    const visit_id = document.getElementById("visit").dataset.id;
    const product_id = parseInt(modal.dataset.productId);
    const quantity = parseInt(modal.dataset.quantity);

    const product = products.find(p => p.id === product_id);

    // クライアント側で即時更新
    const order = {
        product_id,
        product_name: product.name,
        product_image_path: product.image_path,
        quantity,
        visit_id,
    };
    await addOrder(order);

    // 合計金額を加算（税抜き）
    total += product.price * quantity;

    // 即時に注文履歴表示を更新
    renderOrder();
    closeModal();
}

function renderOrder() {
    orderList.innerHTML = "";
    if (orders.length === 0) return;

    orders.forEach(order => {
        const li = document.createElement("li");
        li.innerHTML = `
        <div class="flex justify-start items-center mb-2">
            <img src="${order.product_image_path}" alt="${order.product_name}" class="w-16 m-2">
            <div class="font-bold">${order.product_name}</div>
            <span class="ml-auto px-3 py-1 text-white bg-green-500 rounded">${order.quantity}</span>
        </div>
        `;
        orderList.appendChild(li);
    });

    const totalWithTax = Math.round(total * TAX_RATE);
    totalDisplay.textContent = `合計：${total}円（税込${totalWithTax}円）`;
}

document.getElementById("checkout-button").addEventListener("click", (e) => {
    e.preventDefault();

    // 注文履歴HTML生成
    const orderItemsHTML = orders.map(order => `
        <div class="flex justify-between items-center border-b py-2">
            <div>${order.product_name}</div>
            <div class="text-gray-600">×${order.quantity}</div>
        </div>
    `).join("");

    modalContent.innerHTML = `
        <div class="w-1/2 mx-auto">
            <h2 class="text-2xl font-bold text-center mb-4">はる寿司</h2>
            <h2 class="text-2xl font-bold text-center mb-4">お会計</h2>

            <div class="p-4 rounded mb-4">
                <h3 class="text-lg font-semibold mb-2">注文履歴</h3>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    ${orderItemsHTML || '<p class="text-gray-500">注文はありません。</p>'}
                </div>
            </div>

            <p class="text-center mb-6 text-lg">
                合計：<span class="font-bold">${total}円（税込${Math.round(total * TAX_RATE)}円）</span>
            </p>

            <div class="text-center text-xl p-4 font-bold">この内容でお会計しますか？</div>
            <div class="flex justify-center gap-4">
                <button id="confirm-checkout" href="./complete.html" class="bg-sky-600 text-white px-6 py-3 rounded hover:bg-sky-700 transition">
                    はい
                </button>
                <button onclick="closeModal()" class="border border-sky-500 text-sky-600 px-6 py-3 rounded hover:bg-sky-100 transition">
                    いいえ
                </button>
            </div>
        </div>
    `;
    modal.classList.remove("hidden");
});

modalContent.addEventListener("click", (e) => {
    const target = e.target;

    if (target && target.id === "confirm-checkout") {
        //localStorageを削除
        localStorage.removeItem("orders");
        localStorage.removeItem("total");

        //会計ページに移動
        window.location.href = "./complete.html";
    }
});

loadProducts();
loadOrders();