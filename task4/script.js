const API = "http://localhost:5000";

async function loadOrders() {
    const res = await fetch(`${API}/orders`);
    const data = await res.json();

    const tbody = document.getElementById("orderTable");
    tbody.innerHTML = "";

    data.forEach(order => {
        tbody.innerHTML += `
            <tr>
                <td>${order.customer}</td>
                <td>${order.product}</td>
                <td>${order.quantity}</td>
                <td>${order.price}</td>
                <td>${order.total}</td>
                <td>${order.order_date.split("T")[0]}</td>
            </tr>
        `;
    });
}

async function loadHighest() {
    const res = await fetch(`${API}/orders/highest`);
    const data = await res.json();
    document.getElementById("highest").innerText =
        `${data.name} - ₹${data.total}`;
}

async function loadActive() {
    const res = await fetch(`${API}/customers/active`);
    const data = await res.json();
    document.getElementById("active").innerText =
        `${data.name} (${data.total_orders} orders)`;
}

loadOrders();
loadHighest();
