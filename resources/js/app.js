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
});
