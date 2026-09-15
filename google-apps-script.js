var SPREADSHEET_ID = "1LQgXlBv5mJ8YdXICO_F7l45Ci5abgcD-wSDjb-jKCAs";

var SHEET_CONFIG = {
  contact_message: {
    sheetName: "Contact Messages",
    headers: [
      "Timestamp",
      "Full Name",
      "Phone Number",
      "Email Address",
      "Subject",
      "Message",
      "Submitted From URL",
    ],
    mapRow: function (data, timestamp) {
      return [
        timestamp,
        data.name || data.customer_name || "",
        data.phone || data.mobile || "",
        data.email || "",
        data.subject || "General Inquiry",
        data.message || data.notes || "",
        data.source_url || data.page_url || "",
      ];
    },
  },

  // 2. Dealership Application (/dealers/become-dealer.html)
  dealer_application: {
    sheetName: "Dealer Applications",
    headers: [
      "Timestamp",
      "Firm / Company Name",
      "Contact Person",
      "Phone / WhatsApp",
      "Email Address",
      "State / Territory",
      "City / District",
      "GSTIN",
      "Dealership Type",
      "Business Background / Notes",
      "Submitted From URL",
    ],
    mapRow: function (data, timestamp) {
      return [
        timestamp,
        data.firm_name || data.company_name || "",
        data.contact_person || data.name || "",
        data.phone || "",
        data.email || "",
        data.state || "",
        data.city || "",
        data.gstin || data.gst_number || "N/A",
        data.dealership_type || "Retail Dealer",
        data.notes || data.message || "",
        data.source_url || data.page_url || "",
      ];
    },
  },

  // 3. Warranty Registration (/support/warranty.html)
  warranty_registration: {
    sheetName: "Warranty Registrations",
    headers: [
      "Timestamp",
      "Customer Full Name",
      "Mobile Phone",
      "Email Address",
      "Product Category",
      "Model Name / Code",
      "Serial No / Batch",
      "Purchase Date",
      "Invoice / Bill No",
      "Dealer / Store Name",
      "Price Paid (INR)",
      "Pincode",
      "Complete Address",
      "Submitted From URL",
    ],
    mapRow: function (data, timestamp) {
      return [
        timestamp,
        data.customer_name || data.name || "",
        data.phone || "",
        data.email || "",
        data.category || data.product_category || "",
        data.model || data.product_model || "",
        data.serial || data.serial_number || "N/A",
        data.purchase_date || "",
        data.invoice_no || data.invoice_number || "",
        data.dealer_name || "",
        data.price || "N/A",
        data.pincode || "",
        data.address || "",
        data.source_url || data.page_url || "",
      ];
    },
  },

  // 4. Customer Service Ticket (/support/service.html)
  service_ticket: {
    sheetName: "Service Requests",
    headers: [
      "Timestamp",
      "Customer Name",
      "Mobile Phone",
      "Appliance Category",
      "Model Name / Code",
      "City / District",
      "Pincode",
      "Issue Description",
      "Submitted From URL",
    ],
    mapRow: function (data, timestamp) {
      return [
        timestamp,
        data.name || data.customer_name || "",
        data.phone || "",
        data.category || "",
        data.model || "",
        data.city || "",
        data.pincode || "",
        data.issue || data.message || "",
        data.source_url || data.page_url || "",
      ];
    },
  },

  // 5. Job & Career Application (/company/careers.html)
  job_application: {
    sheetName: "Job Applications",
    headers: [
      "Timestamp",
      "Applicant Name",
      "Phone Number",
      "Email Address",
      "Role Applied For",
      "Experience Summary / LinkedIn / Notes",
      "Submitted From URL",
    ],
    mapRow: function (data, timestamp) {
      return [
        timestamp,
        data.name || "",
        data.phone || "",
        data.email || "",
        data.role || data.position || "General Application",
        data.notes || data.experience || "",
        data.source_url || data.page_url || "",
      ];
    },
  },

  // 6. B2B Express Dealer Callback (/index.html)
  express_dealer_callback: {
    sheetName: "Express Callbacks",
    headers: [
      "Timestamp",
      "Full Name / Firm",
      "City & State",
      "Phone Number",
      "Submitted From URL",
    ],
    mapRow: function (data, timestamp) {
      return [
        timestamp,
        data.name || "",
        data.city || "",
        data.phone || "",
        data.source_url || data.page_url || "",
      ];
    },
  },

  // 7. Direct Factory Product Inquiry (Modal on PDPs & Navbar)
  product_inquiry: {
    sheetName: "Product Inquiries",
    headers: [
      "Timestamp",
      "Selected Product / SKU",
      "Customer Name",
      "Phone / WhatsApp",
      "Email Address",
      "City / State",
      "Inquiry Type",
      "Requirement / Message Notes",
      "Submitted From URL",
    ],
    mapRow: function (data, timestamp) {
      return [
        timestamp,
        data.product || "General Inquiry",
        data.name || "",
        data.phone || "",
        data.email || "",
        data.city || "",
        data.inquiry_type || "Dealer / Trade Price",
        data.notes || "",
        data.source_url || data.page_url || "",
      ];
    },
  },

  // 8. Newsletter Subscription (Footer)
  newsletter_subscription: {
    sheetName: "Newsletter Subscribers",
    headers: [
      "Timestamp",
      "Subscriber Email Address",
      "Status",
      "Submitted From URL",
    ],
    mapRow: function (data, timestamp) {
      return [
        timestamp,
        data.email || "",
        "Active",
        data.source_url || data.page_url || "",
      ];
    },
  },
};

/**
 * HTTP POST Handler — receives data from PHP backend or directly from website
 */
function doPost(e) {
  try {
    var rawData = {};

    // 1. Parse payload (Supports JSON postData or standard form-urlencoded parameters)
    if (e && e.postData && e.postData.contents) {
      try {
        rawData = JSON.parse(e.postData.contents);
      } catch (jsonErr) {
        rawData = e.parameter || {};
      }
    } else if (e && e.parameter) {
      rawData = e.parameter;
    }

    // 2. Determine Form Type
    var formType = (rawData.formType || rawData.form_type || "contact_message")
      .toString()
      .trim();
    var config = SHEET_CONFIG[formType] || SHEET_CONFIG["contact_message"];

    // 3. Open Spreadsheet
    var spreadsheet;
    var targetSpreadsheetId = rawData.spreadsheet_id || SPREADSHEET_ID;

    if (
      targetSpreadsheetId &&
      targetSpreadsheetId !== "YOUR_SPREADSHEET_ID_HERE"
    ) {
      spreadsheet = SpreadsheetApp.openById(targetSpreadsheetId);
    } else {
      spreadsheet = SpreadsheetApp.getActiveSpreadsheet();
    }

    if (!spreadsheet) {
      throw new Error(
        "Could not open Spreadsheet. Please verify SPREADSHEET_ID.",
      );
    }

    // 4. Get or Create Sheet Tab
    var sheetName = config.sheetName;
    var sheet = spreadsheet.getSheetByName(sheetName);

    if (!sheet) {
      sheet = spreadsheet.insertSheet(sheetName);

      // Initialize Header Row with Maurice Brand Styling
      var headerRange = sheet.getRange(1, 1, 1, config.headers.length);
      headerRange.setValues([config.headers]);
      headerRange.setBackground("#E01E26"); // Maurice Signature Brand Red
      headerRange.setFontColor("#FFFFFF");
      headerRange.setFontWeight("bold");
      headerRange.setFontFamily("Arial");
      headerRange.setHorizontalAlignment("center");
      headerRange.setVerticalAlignment("middle");
      sheet.setRowHeight(1, 36);
      sheet.setFrozenRows(1);

      // Auto-fit column widths
      for (var col = 1; col <= config.headers.length; col++) {
        sheet.setColumnWidth(col, 180);
      }
    }

    // 5. Build Row Data with Formatted Timestamp (IST)
    var timestamp = Utilities.formatDate(
      new Date(),
      "Asia/Kolkata",
      "yyyy-MM-dd HH:mm:ss",
    );
    var rowValues = config.mapRow(rawData, timestamp);

    // 6. Append Row to Sheet
    sheet.appendRow(rowValues);

    // Style the newly inserted data row
    var lastRow = sheet.getLastRow();
    var dataRange = sheet.getRange(lastRow, 1, 1, rowValues.length);
    dataRange.setFontFamily("Arial");
    dataRange.setFontSize(10);
    dataRange.setVerticalAlignment("middle");
    sheet.setRowHeight(lastRow, 28);

    // Optional alternating zebra stripe
    if (lastRow % 2 === 0) {
      dataRange.setBackground("#F9FAFB");
    } else {
      dataRange.setBackground("#FFFFFF");
    }

    // Return Success Response
    return ContentService.createTextOutput(
      JSON.stringify({
        success: true,
        message: "Data logged to sheet '" + sheetName + "' successfully.",
        sheet: sheetName,
        row: lastRow,
        timestamp: timestamp,
      }),
    ).setMimeType(ContentService.MimeType.JSON);
  } catch (error) {
    return ContentService.createTextOutput(
      JSON.stringify({
        success: false,
        message: error.toString(),
      }),
    ).setMimeType(ContentService.MimeType.JSON);
  }
}

/**
 * HTTP GET Handler — for endpoint health checks and browser verification
 */
function doGet(e) {
  return ContentService.createTextOutput(
    JSON.stringify({
      status: "online",
      service: "Maurice Appliances Google Sheets Webhook API",
      version: "2.0",
      supportedForms: Object.keys(SHEET_CONFIG),
    }),
  ).setMimeType(ContentService.MimeType.JSON);
}
