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
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3" id="product-grid" style="padding-bottom: 20px;">
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
        <div class="p-3 bg-white border-bottom position-relative">
            <h5 class="fw-bold mb-3"><i class="fas fa-user-edit me-2 text-primary"></i>Customer Info</h5>
            <div class="input-group mb-2 position-relative">
                <span class="input-group-text bg-light border-0"><i class="fas fa-phone"></i></span>
                <input type="text" x-model="customer.no_hp" @input="customer.no_hp = $event.target.value.replace(/[^0-9]/g, '');" @keyup.debounce.300ms="searchCustomer(customer.no_hp, 'phone')" @click.away="showCustomerDropdown = false" class="form-control bg-light border-0" placeholder="Phone (10-14 digit)" maxlength="14" pattern="[0-9]*" inputmode="numeric">
                
                <!-- Autocomplete Dropdown (Phone) -->
                <div x-show="showCustomerDropdown && customerList.length > 0 && activeSearchField === 'phone'" class="position-absolute start-0 top-100 w-100 bg-white shadow rounded z-3 mt-1" style="max-height: 200px; overflow-y: auto; display: none;">
                    <ul class="list-group list-group-flush border">
                        <template x-for="c in customerList" :key="c.id">
                            <li class="list-group-item list-group-item-action cursor-pointer" @click="selectCustomer(c)">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold" x-text="c.nama_customer"></span>
                                    <span class="text-muted small" x-text="c.no_hp"></span>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <div class="position-relative">
                <input type="text" x-model="customer.nama_customer" @input.debounce.300ms="searchCustomer($event.target.value, 'name')" class="form-control bg-light border-0" placeholder="Customer Name">
                 <!-- Dropdown for Name -->
                 <div x-show="showCustomerDropdown && customerList.length > 0 && activeSearchField === 'name'" class="position-absolute start-0 top-100 w-100 bg-white shadow rounded z-3 mt-1" style="max-height: 200px; overflow-y: auto; display: none;">
                    <ul class="list-group list-group-flush border">
                        <template x-for="c in customerList" :key="c.id">
                            <li class="list-group-item list-group-item-action cursor-pointer" @click="selectCustomer(c)">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold" x-text="c.nama_customer"></span>
                                    <span class="text-muted small" x-text="c.no_hp"></span>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-grow-1 overflow-auto p-3" style="background-color: #f8f9fa;">
            <template x-for="(item, index) in cart" :key="index">
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="w-100 me-2">
                                <h6 class="fw-bold mb-1 text-primary" x-text="item.nama_barang"></h6>
                                <input type="text" x-model="item.nama_project" class="form-control form-control-sm border-0 bg-light py-0 px-2 fst-italic text-muted" placeholder="Project Name (e.g. Backdrop Seminar)" style="font-size: 0.85rem;">
                            </div>
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

                            <!-- Quantity -->
                            <div class="col-4">
                                <div class="input-group input-group-sm">
                                    <button class="btn btn-outline-secondary px-2" @click="item.qty > 1 ? item.qty-- : null; updateSubtotal(item)">-</button>
                                    <input type="number" x-model.number="item.qty" class="form-control text-center px-1" @input="updateSubtotal(item)">
                                    <button class="btn btn-outline-secondary px-2" @click="item.qty++; updateSubtotal(item)">+</button>
                                </div>
                            </div>
                            <!-- Discount Input -->
                            <div class="col-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text px-1">D%</span>
                                    <input type="number" x-model.number="item.diskon_persen" class="form-control text-center px-1" min="0" max="100" @input="updateSubtotal(item)">
                                </div>
                            </div>
                            <!-- Subtotal -->
                            <div class="col-4 text-end">
                                <span class="fw-bold fs-6" x-text="formatRupiah(item.subtotal)"></span>
                                <div x-show="item.diskon_persen > 0" class="text-success small" style="font-size: 0.75rem;" x-text="`Desc ${item.diskon_persen}%`"></div>
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
                <div class="modal-header bg-gradient-primary text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                    <h5 class="modal-title fw-bold"><i class="fas fa-receipt me-2"></i>Payment Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold" x-text="formatRupiah(subTotal)"></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Discount (%)</span>
                            <div class="input-group input-group-sm w-50">
                                <input type="number" x-model="payment.diskon_persen" class="form-control border-0 bg-light text-end" placeholder="0" min="0" max="100" @input="calculateGrandTotal()">
                                <span class="input-group-text border-0 bg-light">%</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mb-3" x-show="payment.diskon_persen > 0">
                             <span class="text-success small" x-text="`Potongan: -${formatRupiah(calculateDiscountAmount())}`"></span>
                        </div>

                        <!-- Ribbon Style Total -->
                        <!-- Ribbon Style Total (Simplified) -->
                        <div class="bg-primary text-white p-3 rounded-3 shadow-lg text-center mb-3 position-relative overflow-hidden">
                             <!-- Subtle sheen effect -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-10" style="transform: skewX(-20deg) translateX(-150%); animation: sheen 3s infinite;"></div>
                            <span class="d-block text-white-50 text-uppercase small fw-bold mb-1">Grand Total</span>
                            <span class="fs-1 fw-bold" x-text="formatRupiah(grandTotal)"></span>
                        </div>
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

                    <!-- Actions (Moved inside Body) -->
                    <div class="d-grid gap-2">
                         <button @click="submitTransaction()" class="btn btn-primary btn-lg shadow-sm py-3 fw-bold" :disabled="processing">
                            <span x-show="!processing"><i class="fas fa-print me-2"></i>CONFIRM ORDER & PRINT</span>
                            <span x-show="processing"><i class="fas fa-spinner fa-spin me-2"></i>Processing...</span>
                        </button>
                        <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cancel Payment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="historyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-history me-2"></i>Order History</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Date</th>
                                    <th>Invoice</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="h in history" :key="h.id">
                                    <tr>
                                        <td class="ps-4" x-text="h.tgl_masuk"></td>
                                        <td class="fw-bold" x-text="h.no_invoice"></td>
                                        <td x-text="formatRupiah(h.grand_total)"></td>
                                        <td>
                                            <span class="badge" :class="h.status_bayar === 'lunas' ? 'bg-success' : 'bg-warning text-dark'" x-text="h.status_bayar"></span>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="history.length === 0">
                                    <td colspan="4" class="text-center py-4 text-muted">No history found for this number.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
        history: [],
        customer: {
            no_hp: '',
            nama_customer: ''
        },
        customerList: [],
        showCustomerDropdown: false,
        payment: {
            amount_paid: 0,
            diskon_persen: 0,
            discount: 0, // Nominal calculated from percent
            method: 'cash',
            estimasi: 1
        },
        processing: false,
        activeSearchField: null,

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

        // Autocomplete Search
        searchCustomer(term, field) {
            this.activeSearchField = field;
            
            if(!term || term.length < 3) {
                this.customerList = [];
                this.showCustomerDropdown = false;
                return;
            }

            fetch(`/pos/searchCustomer?term=${term}`)
                .then(res => res.json())
                .then(data => {
                    this.customerList = data;
                    this.showCustomerDropdown = data.length > 0;
                });
        },

        selectCustomer(c) {
            this.customer.no_hp = c.no_hp;
            this.customer.nama_customer = c.nama_customer;
            this.showCustomerDropdown = false;
        },

        showHistory() {
            if(this.customer.no_hp.length < 5) return;
            
            fetch(`/pos/getCustomerHistory?phone=${this.customer.no_hp}`)
                .then(res => res.json())
                .then(data => {
                    this.history = data;
                    new bootstrap.Modal(document.getElementById('historyModal')).show();
                });
        },

        addToCart(product) {
            // Check if exists
            // Note: For meter items, we might want to allow duplicates if dimensions vary.
            // But for simplicity, let's treat new addition as new line item always if it requires custom inputs.
            
            let item = {
                id: product.id,
                nama_barang: product.nama_barang,
                nama_project: '',
                harga_dasar: parseFloat(product.harga_dasar),
                jenis_harga: product.jenis_harga,
                qty: 1,
                panjang: 1,
                lebar: 1,
                catatan_finishing: '',
                diskon_persen: 0,
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
            let gross = 0;
            if (item.jenis_harga === 'meter') {
                let area = item.panjang * item.lebar;
                gross = (area * item.harga_dasar) * item.qty;
            } else {
                gross = item.harga_dasar * item.qty;
            }
            
            // Apply Discount Percent
            let discountAmount = gross * (item.diskon_persen / 100);
            item.subtotal = gross - discountAmount;
        },

        get subTotal() {
            return this.cart.reduce((sum, item) => sum + item.subtotal, 0);
        },

        get grandTotal() {
            let discAmount = this.subTotal * (this.payment.diskon_persen / 100);
            return this.subTotal - discAmount;
        },
        
        calculateDiscountAmount() {
            return this.subTotal * (this.payment.diskon_persen / 100);
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
            
            if (this.customer.no_hp.length < 10 || this.customer.no_hp.length > 14) {
                alert('Nomor HP harus antara 10 - 14 digit!');
                this.processing = false;
                return;
            }

            // Client Timestamp
            const now = new Date();
            const pad = (n) => n.toString().padStart(2, '0');
            const clientTimestamp = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

            let payload = {
                items: this.cart,
                customer: this.customer,
                total_asli: this.subTotal,
                diskon_persen: this.payment.diskon_persen,
                diskon: this.calculateDiscountAmount(), 
                grand_total: this.grandTotal,
                nominal_bayar: this.payment.amount_paid,
                sisa_bayar: (this.grandTotal - this.payment.amount_paid > 0) ? (this.grandTotal - this.payment.amount_paid) : 0,
                metode_bayar: this.payment.method,
                estimasi_hari: this.payment.estimasi,
                created_at: clientTimestamp
            };

            fetch('/pos/saveTransaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [document.querySelector('meta[name="csrf-header"]').content]: document.querySelector('meta[name="csrf-token"]').content
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
