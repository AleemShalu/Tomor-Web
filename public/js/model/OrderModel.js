class Store {
    constructor(storeData) {
        this.id = storeData.id;
        this.business_type_id = storeData.business_type_id;
        this.commercial_name_en = storeData.commercial_name_en;
        this.commercial_name_ar = storeData.commercial_name_ar;
        this.short_name_en = storeData.short_name_en;
        this.short_name_ar = storeData.short_name_ar;
        this.description = storeData.description;
        this.email = storeData.email;
        this.country_id = storeData.country_id;
        this.dial_code = storeData.dial_code;
        this.contact_no = storeData.contact_no;
        this.secondary_dial_code = storeData.secondary_dial_code;
        this.secondary_contact_no = storeData.secondary_contact_no;
        this.tax_id_number = storeData.tax_id_number;
        this.tax_id_attachment = storeData.tax_id_attachment;
        this.commercial_registration_no = storeData.commercial_registration_no;
        this.commercial_registration_expiry = storeData.commercial_registration_expiry;
        this.commercial_registration_attachment = storeData.commercial_registration_attachment;
        this.municipal_license_no = storeData.municipal_license_no;
        this.api_url = storeData.api_url;
        this.api_admin_url = storeData.api_admin_url;
        this.menu_pdf = storeData.menu_pdf;
        this.website = storeData.website;
        this.logo = storeData.logo;
        this.status = storeData.status;
        this.owner_id = storeData.owner_id;
        this.created_at = storeData.created_at;
        this.updated_at = storeData.updated_at;
    }
}

class StoreBranch {
    constructor(storeBranchData) {
        this.id = storeBranchData.id;
        this.name = storeBranchData.name;
        this.branch_serial_number = storeBranchData.branch_serial_number;
        this.qr_code = storeBranchData.qr_code;
        this.commercial_registration_no = storeBranchData.commercial_registration_no;
        this.commercial_registration_expiry = storeBranchData.commercial_registration_expiry;
        this.commercial_registration_attachment = storeBranchData.commercial_registration_attachment;
        this.bank_account_id = storeBranchData.bank_account_id;
        this.email = storeBranchData.email;
        this.dial_code = storeBranchData.dial_code;
        this.contact_no = storeBranchData.contact_no;
        this.city_id = storeBranchData.city_id;
        this.default_branch = storeBranchData.default_branch;
        this.store_id = storeBranchData.store_id;
        this.created_at = storeBranchData.created_at;
        this.updated_at = storeBranchData.updated_at;
    }
}

class Order {
    constructor(orderData) {
        this.id = orderData.id;
        this.order_number = orderData.order_number;
        this.branch_order_number = orderData.branch_order_number;
        this.branch_queue_number = orderData.branch_queue_number;
        this.status = orderData.status;
        this.order_date = orderData.order_date;
        this.customer_id = orderData.customer_id;
        this.customer_name = orderData.customer_name;
        this.customer_dial_code = orderData.customer_dial_code;
        this.customer_contact_no = orderData.customer_contact_no;
        this.customer_email = orderData.customer_email;
        this.customer_vehicle_id = orderData.customer_vehicle_id;
        this.customer_vehicle_description = orderData.customer_vehicle_description;
        this.customer_vehicle_color = orderData.customer_vehicle_color;
        this.customer_vehicle_plate = orderData.customer_vehicle_plate;
        this.customer_special_needs_qualified = orderData.customer_special_needs_qualified;
        this.items_count = orderData.items_count;
        this.items_quantity = orderData.items_quantity;
        this.exchange_rate = orderData.exchange_rate;
        this.conversion_time = orderData.conversion_time;
        this.order_currency_code = orderData.order_currency_code;
        this.base_currency_code = orderData.base_currency_code;
        this.grand_total = orderData.grand_total;
        this.base_grand_total = orderData.base_grand_total;
        this.sub_total = orderData.sub_total;
        this.base_sub_total = orderData.base_sub_total;
        this.service_total = orderData.service_total;
        this.base_service_total = orderData.base_service_total;
        this.discount_total = orderData.discount_total;
        this.base_discount_total = orderData.base_discount_total;
        this.tax_total = orderData.tax_total;
        this.base_tax_total = orderData.base_tax_total;
        this.taxable_total = orderData.taxable_total;
        this.base_taxable_total = orderData.base_taxable_total;
        this.checkout_method = orderData.checkout_method;
        this.coupon_code = orderData.coupon_code;
        this.is_gift = orderData.is_gift;
        this.is_guest = orderData.is_guest;
        this.store_id = orderData.store_id;
        this.store_branch_id = orderData.store_branch_id;
        this.employee_id = orderData.employee_id;
        this.created_at = orderData.created_at;
        this.updated_at = orderData.updated_at;
        this.store = new Store(orderData.store);
        this.store_branch = new StoreBranch(orderData.store_branch);
    }
}

// Sample order data
const orderData = {
    id: 5,
    order_number: "ORD-486490",
    branch_order_number: "BRANCH-ORD-501924",
    branch_queue_number: "QUEUE-632",
    status: "Finished",
    order_date: "2023-07-30 20:39:12",
    customer_id: null,
    customer_name: "Vladimir Abshire",
    customer_dial_code: "+966",
    customer_contact_no: "4370749095",
    customer_email: "mherzog@example.com",
    customer_vehicle_id: null,
    customer_vehicle_description: "Et est corporis at perspiciatis.",
    customer_vehicle_color: "Red",
    customer_vehicle_plate: "ZQM-823",
    customer_special_needs_qualified: 1,
    items_count: 8,
    items_quantity: 44,
    exchange_rate: "2.2198",
    conversion_time: "2023-07-24 22:42:20",
    order_currency_code: "SAR",
    base_currency_code: "SAR",
    grand_total: "345.2363",
    base_grand_total: "428.6623",
    sub_total: "114.1117",
    base_sub_total: "334.7094",
    service_total: "129.0766",
    base_service_total: "21.2323",
    discount_total: "62.9621",
    base_discount_total: "39.1645",
    tax_total: "39.7329",
    base_tax_total: "34.3366",
    taxable_total: "470.5095",
    base_taxable_total: "66.5350",
    checkout_method: "cash on delivery",
    coupon_code: "DISCOUNT50",
    is_gift: false,
    is_guest: null,
    store_id: 10,
    store_branch_id: 46,
    employee_id: null,
    created_at: "2023-08-02T08:57:26.000000Z",
    updated_at: "2023-08-02T08:57:26.000000Z",
    store: {
        id: 10,
        business_type_id: 5,
        commercial_name_en: "Schuppe, Schmitt and Quitzon",
        commercial_name_ar: "معرض الصقيه",
        short_name_en: "eos",
        short_name_ar: "quia",
        description: "Voluptatum ad ullam qui ipsa.",
        email: "ebashirian@rowe.com",
        country_id: 1,
        dial_code: "996",
        contact_no: "321-526-3932",
        secondary_dial_code: null,
        secondary_contact_no: null,
        tax_id_number: "497815860296546",
        tax_id_attachment: null,
        commercial_registration_no: "2655297680",
        commercial_registration_expiry: "2025-12-19",
        commercial_registration_attachment: null,
        municipal_license_no: "59215345847",
        api_url: null,
        api_admin_url: null,
        menu_pdf: null,
        website: null,
        logo: null,
        status: 0,
        owner_id: 2,
        created_at: "2023-08-02T08:57:22.000000Z",
        updated_at: "2023-08-02T08:57:22.000000Z"
    },
    store_branch: {
        id: 46,
        name: "Bernier-Botsford",
        branch_serial_number: null,
        qr_code: null,
        commercial_registration_no: "2609669710",
        commercial_registration_expiry: "2024-06-08",
        commercial_registration_attachment: null,
        bank_account_id: 1,
        email: "marcelino10@hotmail.com",
        dial_code: "+80155",
        contact_no: "1-667-872-6942",
        city_id: 9,
        default_branch: 0,
        store_id: 4,
        created_at: "2023-08-02T08:57:23.000000Z",
        updated_at: "2023-08-02T08:57:23.000000Z"
    }
};

// Create an Order instance using the sample data
const order = new Order(orderData);

// Access the properties of the order, store, and store_branch
console.log(order.order_number);
console.log(order.store.commercial_name_en);
console.log(order.store_branch.name);

// Export the Order class or instance if needed
export default Order;
