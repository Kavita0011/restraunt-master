document.querySelectorAll('.order-btn').forEach(button => {
    button.addEventListener('click', () => {
        const menuId = button.getAttribute('data-id');
        alert(`Order placed for menu item ID: ${menuId}`);
    });
});
