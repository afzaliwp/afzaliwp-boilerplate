function doGet(e) {
    try {
        // Get the active spreadsheet and its name
        var spreadsheet = SpreadsheetApp.getActiveSpreadsheet();
        var spreadsheetName = spreadsheet.getName();

        // Get the active sheet and its name
        var sheet = spreadsheet.getActiveSheet();
        var sheetName = sheet.getName();

        // Combine spreadsheet name with the sheet name
        var combinedSheetName = spreadsheetName + ":" + sheetName;

        // Get the email of the user executing the script.
        var userEmail = Session.getActiveUser().getEmail();

        // Construct the JSON response object
        var responseObj = {
            success: true,
            sheet: combinedSheetName,
            executorEmail: userEmail
        };

        // Return the JSON response
        return ContentService
            .createTextOutput(JSON.stringify(responseObj))
            .setMimeType(ContentService.MimeType.JSON);

    } catch (error) {
        // For debugging: return error details in JSON format if something goes wrong
        var errorResponse = { error: error.message };
        return ContentService
            .createTextOutput(JSON.stringify(errorResponse))
            .setMimeType(ContentService.MimeType.JSON);
    }
}

function doPost(e) {
    // Prepare JSON output
    var output = ContentService
        .createTextOutput()
        .setMimeType(ContentService.MimeType.JSON);

    // 1) Parse incoming payload (JSON or form‐encoded)
    var payload;
    try {
        if (e.postData && e.postData.type.indexOf('application/json') === 0) {
            payload = JSON.parse(e.postData.contents);
        } else {
            payload = e.parameter;
        }
    }
    catch (parseErr) {
        output.setContent(JSON.stringify({
            success: false,
            message: 'Invalid JSON: ' + parseErr.message
        }));
        return output;
    }

    try {
        var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();

        // 3) Collect and sort all "field_<n>" keys
        var keys = Object.keys(payload)
            .filter(function(k) { return k.indexOf('field_') === 0; })
            .sort(function(a, b) {
                var aNum = parseFloat(a.replace(/[^0-9.]/g, ''));
                var bNum = parseFloat(b.replace(/[^0-9.]/g, ''));
                return aNum - bNum;
            });

        // 4) Build headers & row data
        var headers = keys.map(function(k) {
            return payload['label_' + k] || k;
        });
        var rowData = keys.map(function(k) {
            return payload[k] || '';
        });

        // 5) Append headers if sheet is empty
        if (sheet.getLastRow() === 0) {
            sheet.appendRow(headers);
        }

        // 6) Append the row
        sheet.appendRow(rowData);

        // 7) Return success JSON
        output.setContent(JSON.stringify({
            success: true,
            message: 'Row added'
        }));
        return output;
    }
    catch (err) {
        // 8) Return error JSON
        output.setContent(JSON.stringify({
            success: false,
            message: 'Script error: ' + err.message
        }));
        return output;
    }
}
