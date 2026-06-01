@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-cloud-upload"></i> परिवार बुकिंग - एक्सेल अपलोड</h2>
    </div>

    <!-- Info Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-info border-0 shadow-sm" role="alert">
                <div class="d-flex gap-3 align-items-start">
                    <i class="bi bi-info-circle fs-5 flex-shrink-0 mt-1"></i>
                    <div>
                        <h5 class="alert-heading mb-2">📋 एक्सेल से परिवार बुकिंग डेटा अपलोड करें</h5>
                        <p class="mb-0">एक्सेल फाइल से एक या एकाधिक परिवार बुकिंग को एक साथ जोड़ें। पहले टेम्पलेट डाउनलोड करें और सही प्रारूप में डेटा भरें। अपलोड के बाद, आप किसी भी फील्ड को संपादित कर सकते हैं।</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Excel Upload Card -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0"><i class="bi bi-cloud-upload"></i> एक्सेल फाइल अपलोड</h5>
                </div>
                <div class="card-body p-4">
                    <form id="excelUploadForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- File Input -->
                        <div class="mb-4">
                            <label for="excel_file" class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-excel text-success"></i> एक्सेल फाइल चुनें
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="file" 
                                       class="form-control form-control-lg" 
                                       id="excel_file" 
                                       name="excel_file" 
                                       accept=".xlsx,.xls" 
                                       required>
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-folder"></i>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-2">
                                ✓ समर्थित: XLSX, XLS | अधिकतम आकार: 5MB
                            </small>
                        </div>

                        <!-- Additional Options -->
                        <div class="mb-4">
                            <div class="form-check form-check-lg">
                                <input class="form-check-input" type="checkbox" id="skipValidation" name="skip_validation" value="1">
                                <label class="form-check-label" for="skipValidation">
                                    वैलिडेशन छोड़ें (अगर डेटा सही है तो तेजी से अपलोड करें)
                                </label>
                            </div>
                        </div>

                        <!-- Upload Buttons -->
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" id="uploadExcelBtn" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="bi bi-cloud-upload"></i> फाइल अपलोड करें
                            </button>
                            <a href="{{ route('download-excel-template') }}" class="btn btn-success btn-lg flex-grow-1">
                                <i class="bi bi-download"></i> टेम्पलेट डाउनलोड करें
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4" id="sidebarSection">
            <!-- Instructions Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white border-0">
                    <h5 class="mb-0"><i class="bi bi-lightbulb"></i> निर्देश</h5>
                </div>
                <div class="card-body">
                    <ol class="small">
                        <li class="mb-2"><strong>टेम्पलेट डाउनलोड करें</strong> - नीचे दिए गए बटन से</li>
                        <li class="mb-2"><strong>डेटा भरें</strong> - सही प्रारूप में सभी आवश्यक फील्ड</li>
                        <li class="mb-2"><strong>फाइल चुनें</strong> - अपनी भरी हुई एक्सेल फाइल</li>
                        <li class="mb-2"><strong>अपलोड करें</strong> - डेटा को सिस्टम में जोड़ने के लिए</li>
                        <li class="mb-2"><strong>देखें और संपादित करें</strong> - पूर्वावलोकन में किसी भी फील्ड को क्लिक करके संपादित करें</li>
                        <li className="mb-2"><strong>पुष्टि करें</strong> - संशोधित डेटा की जांच करें और सहेजें</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Width Preview Section -->
    <div id="previewSection" class="card shadow-sm border-0 mb-4" style="display: none;">
        <div class="card-header bg-success text-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-check-circle"></i> डेटा पूर्वावलोकन (Landscape View)</h5>
            <small class="text-white-50">(किसी भी फील्ड को संपादित करने के लिए क्लिक करें)</small>
        </div>
        <div class="card-body p-0">
            <div class="alert alert-warning d-flex gap-2 align-items-center mb-3 m-4 m-b-0" role="alert">
                <i class="bi bi-pencil-square fs-5"></i>
                <div>
                    <strong>💡 सुझाव:</strong> टेबल को दाएं-बाएं स्क्रॉल करके सभी कॉलम देखें। किसी भी सेल पर क्लिक करके संपादित करें।
                </div>
            </div>
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-hover table-sm align-middle mb-0" id="previewTable">
                    <thead class="table-light sticky-top">
                        <tr id="headerRow">
                            <th style="min-width: 120px; position: sticky; left: 0; background: #f8f9fa; z-index: 10;">
                                <strong>#</strong>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="previewBody">
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex gap-2 p-4 pt-0">
                <button type="button" id="confirmUploadBtn" class="btn btn-success btn-lg">
                    <i class="bi bi-check-lg"></i> पुष्टि करें और सहेजें
                </button>
                <button type="button" id="cancelUploadBtn" class="btn btn-secondary btn-lg">
                    <i class="bi bi-x-lg"></i> रद्द करें
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-control-lg, .btn-lg {
        font-size: 1rem;
        padding: 0.75rem 1.25rem;
    }
    
    .input-group-text {
        border: 1px solid #dee2e6;
    }
    
    #previewSection {
        animation: slideIn 0.3s ease-in-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Landscape Table Styles */
    #previewSection .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 5px;
    }
    
    #previewTable thead {
        position: sticky;
        top: 0;
        z-index: 11;
    }
    
    #previewTable th {
        background: linear-gradient(135deg, #4472C4 0%, #3d5fa3 100%);
        color: white;
        border: 1px solid #3d5fa3;
        padding: 8px 4px;
        white-space: normal;
        vertical-align: middle;
    }
    
    #previewTable tbody tr {
        border-bottom: 1px solid #e9ecef;
    }
    
    #previewTable tbody tr:hover {
        background-color: rgba(68, 114, 196, 0.08);
    }
    
    #previewTable .edit-field {
        border: 1px solid #ddd !important;
        transition: all 0.2s ease;
        cursor: text;
        padding: 4px !important;
        border-radius: 3px;
        font-size: 0.85rem;
        background: white !important;
    }

    #previewTable .edit-field:focus {
        background-color: #fff !important;
        border: 2px solid #007bff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        outline: none;
    }

    #previewTable .edit-field:hover {
        background-color: #f8f9fa !important;
        border: 1px solid #999 !important;
    }
    
    /* Sticky row number column */
    #previewTable td[style*="position: sticky"], 
    #previewTable th[style*="position: sticky"] {
        background: #f8f9fa !important;
        border-right: 2px solid #dee2e6;
        font-weight: bold;
        text-align: center;
    }
    
    /* Responsive adjustments */
    @media (max-width: 1200px) {
        #previewTable th, #previewTable td {
            font-size: 0.8rem;
            padding: 4px 2px;
        }
    }
    
    /* Print styles */
    @media print {
        #previewSection {
            break-inside: avoid;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let excelDataArray = []; // Array of records
    
    // Get CSRF Token
    function getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            return token.getAttribute('content');
        }
        return document.querySelector('input[name="_token"]')?.value || '';
    }
    
    // Excel upload button handler
    document.getElementById('uploadExcelBtn').addEventListener('click', function() {
        const fileInput = document.getElementById('excel_file');
        
        if (!fileInput.files.length) {
            Swal.fire('त्रुटि', 'कृपया एक एक्सेल फाइल चुनें।', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('excel_file', fileInput.files[0]);
        formData.append('_token', getCsrfToken());

        Swal.fire({
            title: 'प्रोसेसिंग...',
            text: 'एक्सेल फाइल को पार्स किया जा रहा है...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch("{{ route('parse-excel') }}", {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                excelDataArray = data.data; // Store array of records
                showExcelPreview(data.data, data.total_rows);
            } else {
                Swal.fire('त्रुटि', data.message || 'एक्सेल फाइल पार्स नहीं हो सकी।', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.close();
            Swal.fire('त्रुटि', 'सर्वर त्रुटि: ' + error.message, 'error');
        });
    });

    // Show preview for multiple records
    function showExcelPreview(dataArray, totalRows) {
        const previewBody = document.getElementById('previewBody');
        const headerRow = document.getElementById('headerRow');
        const previewSection = document.getElementById('previewSection');
        const sidebarSection = document.getElementById('sidebarSection');
        
        // Hide sidebar when preview is shown
        if (sidebarSection) {
            sidebarSection.style.display = 'none';
        }
        
        const displayFields = [
            { key: 'name', label: 'नाम', width: '120px' },
            { key: 'father_name', label: 'पिता/पति', width: '130px' },
            { key: 'age', label: 'उम्र', width: '60px' },
            { key: 'phone', label: 'फोन', width: '110px' },
            { key: 'total_persons', label: 'कुल व्यक्ति', width: '90px' },
            { key: 'city', label: 'शहर', width: '100px' },
            { key: 'state', label: 'राज्य', width: '100px' },
            { key: 'aanchal', label: 'अंचल', width: '100px' },
            { key: 'travel_type', label: 'वाहन', width: '80px' },
            { key: 'check_in_date', label: 'आगमन तिथि', width: '110px' },
            { key: 'check_in_time', label: 'आगमन समय', width: '100px' },
            { key: 'check_out_date', label: 'प्रस्थान तिथि', width: '110px' },
            { key: 'check_out_time', label: 'प्रस्थान समय', width: '100px' },
            { key: 'remark', label: 'टिप्पणी', width: '150px' }
        ];
        
        // Create table headers
        let headerHtml = '<th style="min-width: 120px; position: sticky; left: 0; background: #f8f9fa; z-index: 10;"><strong>#</strong></th>';
        displayFields.forEach(field => {
            headerHtml += `<th style="min-width: ${field.width}; text-align: center; font-weight: bold;">
                <small>${field.label}</small>
            </th>`;
        });
        headerRow.innerHTML = headerHtml;
        
        // Create table rows for each record
        let bodyHtml = '';
        dataArray.forEach((record, index) => {
            bodyHtml += `<tr data-record="${index}">
                <td style="min-width: 120px; position: sticky; left: 0; background: #f8f9fa; z-index: 9; font-weight: bold; text-align: center;">
                    ${index + 1}
                </td>`;
            
            displayFields.forEach(field => {
                const value = record[field.key] || '';
                const displayValue = typeof value === 'object' ? JSON.stringify(value) : value;
                const shortValue = displayValue.length > 30 ? displayValue.substring(0, 27) + '...' : displayValue;
                
                bodyHtml += `<td style="min-width: ${field.width}; padding: 6px 4px;">
                    <input type="text" 
                           class="form-control form-control-sm edit-field" 
                           data-record="${index}" 
                           data-field="${field.key}" 
                           value="${escapeHtml(displayValue)}"
                           title="${escapeHtml(displayValue)}"
                           placeholder="${field.label}"
                           style="font-size: 0.85rem; padding: 4px; border: 1px solid #ddd;">
                </td>`;
            });
            
            bodyHtml += '</tr>';
        });
        
        previewBody.innerHTML = bodyHtml;
        previewSection.style.display = 'block';
        
        // Update header with count
        previewSection.querySelector('.card-header').innerHTML = `
            <div class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h5 class="mb-0"><i class="bi bi-check-circle"></i> डेटा पूर्वावलोकन - ${totalRows} रिकॉर्ड</h5>
                </div>
                <small class="text-white-50">सभी डेटा एक साथ दृश्यमान</small>
            </div>
        `;
        
        // Add event listeners for editing
        document.querySelectorAll('.edit-field').forEach(field => {
            field.addEventListener('input', function() {
                const recordIndex = parseInt(this.getAttribute('data-record'));
                const fieldName = this.getAttribute('data-field');
                
                if (excelDataArray[recordIndex]) {
                    excelDataArray[recordIndex][fieldName] = this.value;
                }
                
                // Visual feedback
                this.style.backgroundColor = '#fff3cd';
                setTimeout(() => {
                    this.style.backgroundColor = 'transparent';
                }, 200);
            });
            
            // Better styling on focus
            field.addEventListener('focus', function() {
                this.style.borderColor = '#007bff';
                this.style.boxShadow = '0 0 0 0.2rem rgba(0, 123, 255, 0.25)';
            });
            
            field.addEventListener('blur', function() {
                this.style.borderColor = '#ddd';
                this.style.boxShadow = 'none';
            });
        });
        
        // Scroll to preview section
        document.getElementById('previewSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Confirm and save
    document.getElementById('confirmUploadBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'पुष्टि करें',
            text: `क्या आप ${excelDataArray.length} रिकॉर्ड को सहेजना चाहते हैं?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'हाँ, सहेजें',
            cancelButtonText: 'नहीं'
        }).then((result) => {
            if (result.isConfirmed) {
                saveDataToDatabase();
            }
        });
    });

    // Save data to database
    function saveDataToDatabase() {
        Swal.fire({
            title: 'सहेजा जा रहा है...',
            text: `${excelDataArray.length} रिकॉर्ड को डेटाबेस में सहेजा जा रहा है...`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const formData = new FormData();
        formData.append('records', JSON.stringify(excelDataArray));
        formData.append('_token', getCsrfToken());

        fetch("{{ route('admin.save-excel-data') }}", {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                // Show success message with details
                let message = data.message + '\n\n';
                if (data.failed_rows && data.failed_rows.length > 0) {
                    message += `⚠️ ${data.failed_rows.length} रिकॉर्ड विफल हुए।\n\n`;
                }
                
                Swal.fire({
                    title: '✅ आंशिक सफल',
                    html: `
                        <div style="text-align: left;">
                            <p><strong>${data.saved_count} रिकॉर्ड सफलतापूर्वक सहेजे गए</strong></p>
                            ${data.failed_count > 0 ? `
                                <p style="color: #dc3545;"><strong>${data.failed_count} रिकॉर्ड विफल हुए</strong></p>
                                <p style="font-size: 0.9rem;">विफल रिकॉर्ड को Excel फाइल में डाउनलोड करें:</p>
                                <button id="downloadFailedBtn" class="btn btn-danger btn-sm" style="margin-top: 10px;">
                                    <i class="bi bi-download"></i> विफल रिकॉर्ड डाउनलोड करें
                                </button>
                            ` : ''}
                        </div>
                    `,
                    icon: data.failed_count > 0 ? 'warning' : 'success',
                    confirmButtonText: 'ठीक है',
                    didOpen: () => {
                        if (data.failed_count > 0) {
                            document.getElementById('downloadFailedBtn').addEventListener('click', function() {
                                downloadFailedRows(data.failed_rows);
                            });
                        }
                    }
                }).then(() => {
                    // Reset form
                    document.getElementById('excelUploadForm').reset();
                    document.getElementById('previewSection').style.display = 'none';
                    document.getElementById('sidebarSection').style.display = 'block';
                    document.getElementById('excel_file').value = '';
                    excelDataArray = [];
                });
            } else {
                let errorMsg = data.message || 'डेटा सहेजते समय त्रुटि हुई।';
                if (data.failed_rows && data.failed_rows.length > 0) {
                    errorMsg += '\n\nविफल रिकॉर्ड:\n';
                    data.failed_rows.slice(0, 5).forEach(row => {
                        errorMsg += `• Row ${row.row}: ${row.reason}\n`;
                    });
                }
                Swal.fire('त्रुटि', errorMsg, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.close();
            Swal.fire('त्रुटि', 'सर्वर त्रुटि: ' + error.message, 'error');
        });
    }

    // Download failed rows as Excel
    function downloadFailedRows(failedRows) {
        if (!failedRows || failedRows.length === 0) {
            Swal.fire('त्रुटि', 'कोई विफल रिकॉर्ड नहीं मिल रहा।', 'error');
            return;
        }

        Swal.fire({
            title: 'डाउनलोड हो रहा है...',
            text: 'विफल रिकॉर्ड को Excel में डाउनलोड किया जा रहा है...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch("{{ route('admin.download-failed-rows') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({
                'failed_rows': failedRows
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Download failed: ' + response.statusText);
            }
            return response.blob();
        })
        .then(blob => {
            Swal.close();
            // Create a download link
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'Failed_Entries_' + new Date().toLocaleDateString('en-GB').replace(/\//g, '-') + '.xlsx';
            document.body.appendChild(link);
            link.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(link);
            
            Swal.fire('✅ सफल', 'विफल रिकॉर्ड डाउनलोड हो गए!', 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.close();
            Swal.fire('त्रुटि', 'डाउनलोड करने में त्रुटि: ' + error.message, 'error');
        });
    }

    // Cancel upload
    document.getElementById('cancelUploadBtn').addEventListener('click', function() {
        document.getElementById('previewSection').style.display = 'none';
        document.getElementById('sidebarSection').style.display = 'block';
        excelDataArray = [];
    });

    // Helper function to escape HTML
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
@endpush

@endsection
