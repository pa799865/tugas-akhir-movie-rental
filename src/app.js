document.addEventListener('alpine:init', () => {
    Alpine.data('products', () => ({
        items: [
            { id: 1, name: 'Petaka Gunung Gede', img: 'download.jpg', price: 292000 },
            { id: 2, name: 'Captain Amerika', img: 'captain-amerika.jpeg', price: 292000 },
            { id: 3, name: 'Cleaner', img: 'cleaner.jpeg', price: 292000 },
            { id: 4, name: 'Detective Chinatown', img: 'detective-chinatown.jpeg', price: 80000 },
        ],
    }));

    Alpine.store('cart', {
        items: [],
        total: 0,
        quantity: 0,
        add(newItem) {
        // cek apakah item sudah ada di cart
        const cartItem = this.items.find((item) => item.id === newItem.id);

        // jika belum ada/cart masih kosong
        if (!cartItem) {
            this.items.push({...newItem, quantity: 1, total: newItem.price});
            this.total += newItem.price;
            this.quantity++;
            }
            // jika sudah ada
            else {
                this.items = this.items.map((item) => {
                    if (item.id !== newItem.id) {
                        return item;
                    } else {
                        item.quantity += 1;
                        item.total = item.price * item.quantity;
                        this.total += item.price;
                        this.quantity++;
                        return item;
                    }
                })
                }
                },
        remove(id) {
            // ambil item yang mau diremove berdasarkan idnya
            const cartItem = this.items.find((item) => item.id === id);

            // jika item lebih dari 1
            if (cartItem.quantity > 1) {
                // telusuri 1 1
                this.items = this.items.map((item) => {
                    // jika bukan barang yang diklik
                    if (item.id !== id) {
                        return item;
                    } else {
                        item.quantity --;
                        item.total = item.price * item.quantity;
                        this.quantity --;
                        this.total -= item.price;
                        return item;
                    }
                }
    )} else if  (cartItem.quantity === 1) {
        // jika item hanya 1
        this.items = this.items.filter((item) => item.id !== id);
        this.quantity--;
        this.total -= cartItem.price;
    }
        }
                });
                });

// form validation
const checkoutButton = document.querySelector('.checkout');
checkoutButton.disabled = true;

const form = document.querySelector('#checkoutForm');

form.addEventListener('keyup', function () {
    for (let i = 0; i < form.elements.length; i++) {
        if (form.elements[i].value.length !== 0) {
            checkoutButton.classList.remove('disabled');
            checkoutButton.classList.add('disabled');
        } else {
            return false;
        }
    }
    checkoutButton.disabled = false;
    checkoutButton.classList.remove('disabled');
});

// kirim data ketika tombol checkout di klik
checkoutButton.addEventListener('click', async function (e){
    e.preventDefault();
    const formData = new FormData(form);
    const data = new URLSearchParams(formData);
    const objData = Object.fromEntries(data);
    // const message = formatMessage(objData);
    // window.open('http://wa.me/6283838743859?text=' + encodeURIComponent(message));

    try {
        const response = await fetch('php/placeOrder.php', {
            method: 'POST',
            body: data,
        });
        const token = await response.text();
        window.snap.pay(token);
    } catch (err) {
        console.log(err.message);
    }

});


// kirim pesan ke whatsapp
const formatMessage = (obj) => {
    return `Data Customer
    Nama: ${obj.name}
    Email: ${obj.email}
    No HP: ${obj.phone}
Data Pesanan
    ${JSON.parse(obj.items).map((item) => `${item.name} (${item.quantity} x ${rupiah(item.total)}) \n` )}
    TOTAL: ${rupiah(obj.total)}
    Terima Kasih.`;
};

// konversi ke rupiah
const rupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        }).format(number);
};