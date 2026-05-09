import "./bootstrap";
import "./currency"; // Legacy support
import "./currencyUtils";
import "./percentageUtils";
import "./calculationUtils";
import "./taxUtils";

import "flowbite";

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

// Import utility modules for Alpine compatibility
import currencyUtils from "./currencyUtils.js";
import percentageUtils from "./percentageUtils.js";
import calculationUtils from "./calculationUtils.js";
import taxUtils from "./taxUtils.js";

Alpine.plugin(collapse);

// Register utilities globally for Alpine.js
window.Currency = currencyUtils;
window.Percentage = percentageUtils;
window.Calculation = calculationUtils;
window.Tax = taxUtils;
window.Alpine = Alpine;

// Optional: Alpine stores for reactive state (uncomment if needed)
// Alpine.store('currency', currencyUtils);
// Alpine.store('percentage', percentageUtils);
// Alpine.store('calculation', calculationUtils);

Alpine.start();
