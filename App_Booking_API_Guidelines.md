# App Booking API Guidelines

This document outlines the REST APIs for creating Family and Group Bookings directly from the Mobile Application.

---

## 1. Family Booking API

**Endpoint**: `/api/app-booking/family`  
**Method**: `POST`  
**Content-Type**: `application/json`  

### Payload Structure

```json
{
    "name": "Raj Kumar",
    "father_name": "Mohan Prasad",
    "phone": "9876543210",
    "age": 35,
    "gender": "male",
    "aadhar_number": "123456789012",
    "mid": "202907",
    "ms_name": "",
    "city": "Jaipur",
    "state": "Rajasthan",
    "aanchal": "Nokha",
    "travel_type": "Train",
    "check_in_date": "2026-07-05",
    "check_out_date": "2026-07-08",
    "check_in_time": "10:30",
    "check_out_time": "14:00",
    "family_coming": "1",
    "no_of_people": 2,
    "no_of_children": 0,
    "total_male": 2,
    "total_female": 1,
    "sixty_plus_members": 0,
    "sixty_plus_male": 0,
    "sixty_plus_female": 0,
    "is_veer_parivar": false,
    "veer_relation": "",
    "remark": "Any special requirements",
    "family_members": [
        {
            "name": "Sunita",
            "father_name": "Raj Kumar",
            "mobile": "9876543211",
            "age": 32,
            "gender": "female",
            "aadhar_number": "987654321098"
        },
        {
            "name": "Rohan",
            "father_name": "Raj Kumar",
            "mobile": "",
            "age": 12,
            "gender": "male",
            "aadhar_number": ""
        }
    ]
}
```

### Response

**Success (201 Created)**
```json
{
    "success": true,
    "message": "Family booking created successfully",
    "data": {
        "id": 150,
        "booking_id": "F-250"
    }
}
```

**Validation Error (422 Unprocessable Entity)**
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "phone": ["The phone field must be 10 digits."]
    }
}
```

---

## 2. Group Booking API

**Endpoint**: `/api/app-booking/group`  
**Method**: `POST`  
**Content-Type**: `application/json`  

### Payload Structure

```json
{
    "name": "Vikram Singh",
    "father_name": "Ramesh Singh",
    "relationship_type": "Son of",
    "phone": "8824103180",
    "aadhar_number": "444455556666",
    "mid": "30590",
    "city": "Bikaner",
    "state": "Rajasthan",
    "aanchal": "Bikaner",
    "travel_type": "Bus",
    "check_in_date": "2026-08-10",
    "check_out_date": "2026-08-12",
    "check_in_time": "09:00",
    "check_out_time": "18:00",
    "total_members": 2,
    "total_male": 3,
    "total_female": 0,
    "child_count": 0,
    "sixty_plus_members": 0,
    "sixty_plus_male": 0,
    "sixty_plus_female": 0,
    "remark": "Coming for convention",
    "members": [
        {
            "name": "Ajay",
            "mobile_number": "8824103181"
        },
        {
            "name": "Vijay",
            "mobile_number": ""
        }
    ]
}
```

### Response

**Success (201 Created)**
```json
{
    "success": true,
    "message": "Group booking created successfully",
    "data": {
        "id": 85,
        "booking_id": "G-185"
    }
}
```

**Validation Error (422 Unprocessable Entity)**
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "total_members": ["The total members field is required."]
    }
}
```

---

> [!NOTE]
> - Both APIs automatically trigger an SMS to the user's primary `phone` number upon successful creation.
> - The APIs are currently public and do not require Bearer authentication. If required in the future, standard Laravel Sanctum tokens can be integrated.
