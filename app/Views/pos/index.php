<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row g-0 h-100" x-data="posApp()">
    <!-- LEFT: Product Catalog -->
    <div class="col-md-7 col-lg-8 p-3 bg-light border-end" style="height: calc(100vh - 60px); overflow-y: auto;">
        
        <!-- Search Bar -->
        <div class="mb-4 d-flex gap-2">
            <input type="text" x-model="searchQuery" @input.debounce="searchProduct()" class="form-control form-control-lg border-0 shadow-sm" placeholder="Scan Barcode or Search Product...">
            <button class="btn btn-primary px-4"><i class="fas fa-search"></i></button>
        </div>

        <!-- Product Grid -->
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3" id="product-grid">
            <template x-for="product in products" :key="product.id">
                <div class="col">
                    <div class="card h-100 product-card border-0 shadow-sm" @click="addToCart(product)">
                        <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                            <div>
                                <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-box fa-lg"></i>
                                </div>
                                <h6 class="card-title fw-bold" x-text="product.nama_barang"></h6>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-yellow-accent" x-show="product.jenis_harga === 'meter'">METER</span>
                                <div class="fs-5 fw-bold mt-1 text-primary-emphasis" x-text="formatRupiah(product.harga_dasar)"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        
    </div>

    <!-- RIGHT: Cart & Checkout -->
    <div class="col-md-5 col-lg-4 bg-white d-flex flex-column shadow-lg" style="height: calc(100vh - 60px);">
        
        <!-- Customer Info -->
        <div class="p-3 bg-white border-bottom">
            <h5 class="fw-bold mb-3"><i class="fas fa-user-edit me-2 text-primary"></i>Customer Info</h5>
            <div class="input-group mb-2">
                <span class="input-group-text bg-light border-0"><i class="fas fa-phone"></i></span>
                <input type="text" x-model="customer.no_hp" @change="checkCustomer()" @input="customer.no_hp = customer.no_hp.replace(/[^0-9]/g, '')" class="form-control bg-light border-0" placeholder="Phone (Numbers only, min 10)" maxlength="15">
            </div>
            <input type="text" x-model="customer.nama_customer" class="form-control bg-light border-0" placeholder="Customer Name">
        </div>

        <!-- Cart Items -->
        <div class="flex-grow-1 overflow-auto p-3" style="background-color: #f8f9fa;">
            <template x-for="(item, index) in cart" :key="index">
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0 text-primary" x-text="item.nama_barang"></h6>
                            <button @click="removeFromCart(index)" class="btn btn-sm text-danger p-0"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <!-- Dynamic Inputs based on Type -->
                        <div class="row g-2 align-items-center mb-2">
                            <!-- Meter Inputs -->
                            <template x-if="item.jenis_harga === 'meter'">
                                <div class="col-12 d-flex gap-2">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">P (m)</span>
                                        <input type="number" x-model.number="item.panjang" class="form-control" step="0.1" @input="updateSubtotal(item)">
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">L (m)</span>
                                        <input type="number" x-model.number="item.lebar" class="form-control" step="0.1" @input="updateSubtotal(item)">
                                    </div>
                                </div>
                            </template>

                            <div class="col-6">
                                <div class="input-group input-group-sm">
                                    <button class="btn btn-outline-secondary" @click="item.qty > 1 ? item.qty-- : null; updateSubtotal(item)">-</button>
                                    <input type="number" x-model.number="item.qty" class="form-control text-center" @input="updateSubtotal(item)">
                                    <button class="btn btn-outline-secondary" @click="item.qty++; updateSubtotal(item)">+</button>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <span class="fw-bold fs-6" x-text="formatRupiah(item.subtotal)"></span>
                            </div>
                        </div>

                        <!-- Finishing Note -->
                        <textarea x-model="item.catatan_finishing" class="form-control form-control-sm bg-light" rows="1" placeholder="Finishing: Mata ayam, Lipat, dll..."></textarea>
                    </div>
                </div>
            </template>
            
            <div x-show="cart.length === 0" class="text-center py-5 text-muted">
                <i class="fas fa-shopping-basket fa-3x mb-3 text-secondary opacity-25"></i>
                <p>Cart is empty</p>
            </div>
        </div>

        <!-- Totals & Checkout -->
        <div class="p-4 bg-white border-top shadow-lg z-3">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Total</span>
                <span class="fw-bold fs-4 text-primary" x-text="formatRupiah(grandTotal)"></span>
            </div>
            
            <button @click="showPaymentModal()" class="btn btn-warning w-100 fw-bold py-3 rounded-3 shadow-sm text-dark" :disabled="cart.length === 0">
                <i class="fas fa-cash-register me-2"></i> PROCESS PAYMENT
            </button>
        </div>

    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Payment Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subtotal</label>
                        <div class="fs-4 fw-bold text-secondary mb-2" x-text="formatRupiah(subTotal)"></div>

                        <label class="form-label">Discount</label>
                        <input type="number" x-model="payment.discount" class="form-control mb-3" placeholder="0" @input="calculateGrandTotal()">

                        <label class="form-label fw-bold border-top pt-2">Grand Total</label>
                        <div class="fs-2 fw-bold text-primary mb-3" x-text="formatRupiah(grandTotal)"></div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Payment Method</label>
                            <select x-model="payment.method" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Estimation (Days)</label>
                            <input type="number" x-model="payment.estimasi" class="form-control" value="1" min="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Amount Paid (DP)</label>
                        <input type="number" x-model="payment.amount_paid" class="form-control form-control-lg border-primary" placeholder="Enter amount...">
                        <div class="form-text mt-2" :class="calculateChange() < 0 ? 'text-danger fw-bold' : 'text-success fw-bold'">
                            <span x-text="calculateChange() < 0 ? 'Remaining: ' + formatRupiah(Math.abs(calculateChange())) : 'Change: ' + formatRupiah(calculateChange())"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light w-100 mb-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary w-100 fw-bold py-2" @click="submitTransaction()" :disabled="processing">
                        <span x-show="!processing">CONFIRM ORDER</span>
                        <span x-show="processing"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function posApp() {
    return {
        searchQuery: '',
        products: [],
        cart: [],
        customer: {
            no_hp: '',
            nama_customer: ''
        },
        payment: {
            amount_paid: 0,
            discount: 0,
            method: 'cash',
            estimasi: 1
        },
        processing: false,

        init() {
            this.searchProduct();
        },

        searchProduct() {
            fetch(`/pos/searchProduct?term=${this.searchQuery}`)
                .then(res => res.json())
                .then(data => {
                    this.products = data;
                });
        },

        checkCustomer() {
            if(this.customer.no_hp.length > 5) {
                fetch(`/pos/checkCustomer?phone=${this.customer.no_hp}`)
                    .then(res => res.json())
                    .then(res => {
                        if(res.status == 'found') {
                            this.customer.nama_customer = res.data.nama_customer;
                        }
                    });
            }
        },

        addToCart(product) {
            // Check if exists
            // Note: For meter items, we might want to allow duplicates if dimensions vary.
            // But for simplicity, let's treat new addition as new line item always if it requires custom inputs.
            
            let item = {
                id: product.id,
                nama_barang: product.nama_barang,
                harga_dasar: parseFloat(product.harga_dasar),
                jenis_harga: product.jenis_harga,
                qty: 1,
                panjang: 1,
                lebar: 1,
                catatan_finishing: '',
                subtotal: 0
            };
            
            this.calculateItemSubtotal(item);
            this.cart.push(item);
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        updateSubtotal(item) {
            this.calculateItemSubtotal(item);
        },

        calculateItemSubtotal(item) {
            if (item.jenis_harga === 'meter') {
                // Formula: (P * L * Price) * Qty
                let area = item.panjang * item.lebar;
                // Optional: Min area rule? area = Math.max(1, area);
                item.subtotal = (area * item.harga_dasar) * item.qty;
            } else {
                item.subtotal = item.harga_dasar * item.qty;
            }
        },

        get subTotal() {
            return this.cart.reduce((sum, item) => sum + item.subtotal, 0);
        },

        get grandTotal() {
            return this.subTotal - (parseInt(this.payment.discount) || 0);
        },

        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        },

        showPaymentModal() {
            let modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
            // Default amount paid to total
            // Default amount paid to grand total, discount 0 reset if needed? No keep it.
            // this.payment.amount_paid = this.grandTotal; // Don't auto-fill, let them type? Or auto-fill remainder.
            // Let's auto-fill for convenience
            this.payment.amount_paid = this.grandTotal;
        },

        calculateChange() {
            return this.payment.amount_paid - this.grandTotal;
        },

        submitTransaction() {
            this.processing = true;
            
            let payload = {
                items: this.cart,
                customer: this.customer,
                customer: this.customer,
                total_asli: this.subTotal,
                diskon: this.payment.discount,
                grand_total: this.grandTotal,
                nominal_bayar: this.payment.amount_paid,
                sisa_bayar: (this.grandTotal - this.payment.amount_paid > 0) ? (this.grandTotal - this.payment.amount_paid) : 0,
                metode_bayar: this.payment.method,
                estimasi_hari: this.payment.estimasi
            };

            fetch('/pos/saveTransaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                this.processing = false;
                if (data.status === 'success') {
                    // Reset
                    this.cart = [];
                    this.customer = { no_hp: '', nama_customer: '' };
                    bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
                    
                    // Open Invoice
                    window.open(`/pos/printInvoice/${data.transaction_id}`, '_blank');
                } else {
                    alert('Transaction Failed: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                this.processing = false;
                alert('An error occurred.');
            });
        }
    }
}
</script>

<?= $this->endSection() ?>
