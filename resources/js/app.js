    // --- Giao dịch: chọn đơn hàng, hiển thị tổng tiền, thanh toán toàn bộ ---
    let orderSelect = document.getElementById('order_id_select');
    let orderTotalBox = document.getElementById('order_total_box');
    let orderTotalText = document.getElementById('order_total_text');
    let payFullOrder = document.getElementById('pay_full_order');
    let amountInput = document.getElementById('amount_input');
    let currentOrderTotal = 0;
    if (orderSelect) {
        orderSelect.addEventListener('change', function() {
            // Reset thông tin khách hàng khi chọn đơn hàng
            let customerIdInput = document.getElementById('customer_id');
            let customerNameInput = document.getElementById('customer_name');
            if (customerIdInput) customerIdInput.value = '';
            if (customerNameInput) customerNameInput.value = '';
            let orderId = orderSelect.value;
            if (orderId) {
                fetch('/orders/ajax/total?order_id=' + orderId)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            currentOrderTotal = data.total;
                            orderTotalText.textContent = data.total.toLocaleString('vi-VN') + ' đ';
                            orderTotalBox.style.display = '';
                        } else {
                            orderTotalBox.style.display = 'none';
                        }
                    });
            } else {
                orderTotalBox.style.display = 'none';
            }
        });
    }
    if (payFullOrder && amountInput) {
        payFullOrder.addEventListener('change', function() {
            if (payFullOrder.checked && currentOrderTotal) {
                amountInput.value = currentOrderTotal;
                amountInput.readOnly = true;
            } else {
                amountInput.readOnly = false;
            }
        });
    }
    // Nếu chọn lại đơn hàng khác, bỏ check "thanh toán toàn bộ"
    if (orderSelect && payFullOrder) {
        orderSelect.addEventListener('change', function() {
            payFullOrder.checked = false;
            amountInput.readOnly = false;
        });
    }
import './bootstrap';

document.addEventListener("DOMContentLoaded", function () {
    // Hàm format số có dấu phân cách
    function formatNumber(value) {
        if (!value) return "";
        return value.replace(/\D/g, "")  // loại bỏ ký tự không phải số
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ","); // thêm dấu ,
    }

    // Lặp qua tất cả input có class "format-number"
    document.querySelectorAll(".format-number").forEach(function(input) {
        // Khi gõ
        input.addEventListener("input", function(e) {
            let cursor = input.selectionStart; 
            let beforeLength = input.value.length;

            input.value = formatNumber(input.value);

            // Giữ nguyên vị trí con trỏ khi đang gõ
            let afterLength = input.value.length;
            input.selectionEnd = cursor + (afterLength - beforeLength);
        });

        // Khi submit form: bỏ dấu phẩy để lưu DB
        input.form?.addEventListener("submit", function() {
            input.value = input.value.replace(/,/g, "");
        });
    });
    // --- Popup chọn khách hàng ---
    function loadCustomerList(params = {}) {
        let url = '/customers/popup/search?'+new URLSearchParams(params).toString();
        fetch(url)
            .then(res => res.json())
            .then(data => {
                document.getElementById('customerList').innerHTML = data.html;
            });
    }

    // Khi mở modal thì load danh sách
    let customerModal = document.getElementById('customerModal');
    if (customerModal) {
        let customerListLoaded = false;
        customerModal.addEventListener('show.bs.modal', function () {
            if (!customerListLoaded) {
                loadCustomerList();
                customerListLoaded = true;
            }
            document.getElementById('addCustomerForm').style.display = 'none';
        });
    }

    // Tìm kiếm (reset lại trạng thái đã load để cho phép load lại khi tìm kiếm)
    ['searchName','searchPhone','searchEmail'].forEach(function(id) {
        let el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', function() {
                loadCustomerList({
                    name: document.getElementById('searchName').value,
                    phone: document.getElementById('searchPhone').value,
                    email: document.getElementById('searchEmail').value
                });
                if (customerModal) customerListLoaded = true;
            });
        }
    });

    // Phân trang ajax
    document.addEventListener('click', function(e) {
        if (e.target.closest('#customerList .pagination a')) {
            e.preventDefault();
            let url = e.target.getAttribute('href');
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('customerList').innerHTML = data.html;
                });
        }
    });

    // Chọn khách hàng
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-select-customer')) {
            let id = e.target.getAttribute('data-id');
            let name = e.target.getAttribute('data-name');
            document.getElementById('customer_id').value = id;
            document.getElementById('customer_name').value = name;
            // Reset chọn đơn hàng và tổng tiền
            if (orderSelect) orderSelect.value = '';
            if (orderTotalBox) orderTotalBox.style.display = 'none';
            if (payFullOrder) payFullOrder.checked = false;
            if (amountInput) {
                amountInput.value = '';
                amountInput.readOnly = false;
            }
            let modal = bootstrap.Modal.getInstance(document.getElementById('customerModal'));
            modal.hide();
        }
    });

    // Hiện form thêm mới
    let btnShowAdd = document.getElementById('btnShowAddCustomer');
    if (btnShowAdd) {
        btnShowAdd.addEventListener('click', function() {
            document.getElementById('addCustomerForm').style.display = '';
        });
    }
    // Ẩn form thêm mới
    let btnCancelAdd = document.getElementById('btnCancelAddCustomer');
    if (btnCancelAdd) {
        btnCancelAdd.addEventListener('click', function() {
            document.getElementById('addCustomerForm').style.display = 'none';
        });
    }
    // Submit thêm khách hàng
    let formAdd = document.getElementById('formAddCustomer');
    if (formAdd) {
        formAdd.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(formAdd);
            fetch('/customers/popup/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]')?.value
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('customer_id').value = data.customer.id;
                    document.getElementById('customer_name').value = data.customer.name;
                    let modal = bootstrap.Modal.getInstance(document.getElementById('customerModal'));
                    modal.hide();
                }
            });
        });
    }
});
