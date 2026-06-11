<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicField extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_type',
        'label',
        'name',
        'type',
        'options',
        'is_required',
        'status',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'status' => 'boolean',
    ];

    public static function getStaticFields($formType)
    {
        $fields = [];
        if ($formType == 'family') {
            $fields = [
                ['id' => 'static_name', 'name' => 'name', 'label' => 'नाम', 'type' => 'text', 'is_static' => true, 'order' => 100],
                ['id' => 'static_father_name', 'name' => 'father_name', 'label' => 'पिता/पति का नाम', 'type' => 'text', 'is_static' => true, 'order' => 200],
                ['id' => 'static_age', 'name' => 'age', 'label' => 'उम्र', 'type' => 'number', 'is_static' => true, 'order' => 300],
                ['id' => 'static_gender', 'name' => 'gender', 'label' => 'लिंग', 'type' => 'select', 'is_static' => true, 'order' => 400],
                ['id' => 'static_phone', 'name' => 'phone', 'label' => 'मोबाइल नंबर', 'type' => 'text', 'is_static' => true, 'order' => 500],
                ['id' => 'static_aadhar_number', 'name' => 'aadhar_number', 'label' => 'आधार नंबर', 'type' => 'text', 'is_static' => true, 'order' => 600],
                ['id' => 'static_mid', 'name' => 'mid', 'label' => 'MID', 'type' => 'text', 'is_static' => true, 'order' => 700],
                ['id' => 'static_city', 'name' => 'city', 'label' => 'शहर', 'type' => 'text', 'is_static' => true, 'order' => 800],
                ['id' => 'static_state', 'name' => 'state', 'label' => 'राज्य', 'type' => 'text', 'is_static' => true, 'order' => 900],
                ['id' => 'static_aanchal', 'name' => 'aanchal', 'label' => 'अंचल', 'type' => 'text', 'is_static' => true, 'order' => 1000],
                ['id' => 'static_is_veer_parivar', 'name' => 'is_veer_parivar', 'label' => 'क्या आप वीर परिवार से हैं?', 'type' => 'radio', 'is_static' => true, 'order' => 1100],
                ['id' => 'static_veer_relation', 'name' => 'veer_relation', 'label' => 'रिश्ता', 'type' => 'select', 'is_static' => true, 'order' => 1200],
                ['id' => 'static_ms_name', 'name' => 'ms_name', 'label' => 'परिवार से दीक्षित म.सा.का नाम', 'type' => 'text', 'is_static' => true, 'order' => 1300],
                ['id' => 'static_family_coming', 'name' => 'family_coming', 'label' => 'क्या परिवार के अन्य सदस्य भी आ रहे हैं?', 'type' => 'radio', 'is_static' => true, 'order' => 1400],
                ['id' => 'static_no_of_children', 'name' => 'no_of_children', 'label' => 'बच्चो की संख्या', 'type' => 'number', 'is_static' => true, 'order' => 1500],
                ['id' => 'static_total_male', 'name' => 'total_male', 'label' => 'कुल पुरुष', 'type' => 'number', 'is_static' => true, 'order' => 1600],
                ['id' => 'static_total_female', 'name' => 'total_female', 'label' => 'कुल महिला', 'type' => 'number', 'is_static' => true, 'order' => 1700],
                ['id' => 'static_travel_type', 'name' => 'travel_type', 'label' => 'आने का वाहन', 'type' => 'select', 'is_static' => true, 'order' => 1800],
                ['id' => 'static_check_in_date', 'name' => 'check_in_date', 'label' => 'आगमन की दिनांक', 'type' => 'date', 'is_static' => true, 'order' => 1900],
                ['id' => 'static_check_in_time', 'name' => 'check_in_time', 'label' => 'आगमन का समय', 'type' => 'time', 'is_static' => true, 'order' => 2000],
                ['id' => 'static_check_out_date', 'name' => 'check_out_date', 'label' => 'प्रस्थान की दिनांक', 'type' => 'date', 'is_static' => true, 'order' => 2100],
                ['id' => 'static_check_out_time', 'name' => 'check_out_time', 'label' => 'प्रस्थान का समय', 'type' => 'time', 'is_static' => true, 'order' => 2200],
                ['id' => 'static_remark', 'name' => 'remark', 'label' => 'रिमार्क', 'type' => 'text', 'is_static' => true, 'order' => 2300],
            ];
        } else if ($formType == 'group') {
            $fields = [
                ['id' => 'static_name', 'name' => 'name', 'label' => 'संघ/ग्रुप का नाम', 'type' => 'text', 'is_static' => true, 'order' => 100],
                ['id' => 'static_phone', 'name' => 'phone', 'label' => 'मोबाइल नंबर', 'type' => 'text', 'is_static' => true, 'order' => 200],
                ['id' => 'static_aadhar_number', 'name' => 'aadhar_number', 'label' => 'आधार नंबर', 'type' => 'text', 'is_static' => true, 'order' => 300],
                ['id' => 'static_mid', 'name' => 'mid', 'label' => 'MID', 'type' => 'text', 'is_static' => true, 'order' => 400],
                ['id' => 'static_city', 'name' => 'city', 'label' => 'शहर', 'type' => 'text', 'is_static' => true, 'order' => 500],
                ['id' => 'static_state', 'name' => 'state', 'label' => 'राज्य', 'type' => 'text', 'is_static' => true, 'order' => 600],
                ['id' => 'static_aanchal', 'name' => 'aanchal', 'label' => 'अंचल', 'type' => 'text', 'is_static' => true, 'order' => 700],
                ['id' => 'static_total_members', 'name' => 'total_members', 'label' => 'कुल सदस्य', 'type' => 'number', 'is_static' => true, 'order' => 800],
                ['id' => 'static_total_male', 'name' => 'total_male', 'label' => 'कुल पुरुष', 'type' => 'number', 'is_static' => true, 'order' => 900],
                ['id' => 'static_total_female', 'name' => 'total_female', 'label' => 'कुल महिला', 'type' => 'number', 'is_static' => true, 'order' => 1000],
                ['id' => 'static_child_count', 'name' => 'child_count', 'label' => 'बच्चो की संख्या', 'type' => 'number', 'is_static' => true, 'order' => 1100],
                ['id' => 'static_travel_type', 'name' => 'travel_type', 'label' => 'आने का वाहन', 'type' => 'select', 'is_static' => true, 'order' => 1200],
                ['id' => 'static_check_in_date', 'name' => 'check_in_date', 'label' => 'आगमन की दिनांक', 'type' => 'date', 'is_static' => true, 'order' => 1300],
                ['id' => 'static_check_in_time', 'name' => 'check_in_time', 'label' => 'आगमन का समय', 'type' => 'time', 'is_static' => true, 'order' => 1400],
                ['id' => 'static_check_out_date', 'name' => 'check_out_date', 'label' => 'प्रस्थान की दिनांक', 'type' => 'date', 'is_static' => true, 'order' => 1500],
                ['id' => 'static_check_out_time', 'name' => 'check_out_time', 'label' => 'प्रस्थान का समय', 'type' => 'time', 'is_static' => true, 'order' => 1600],
                ['id' => 'static_remark', 'name' => 'remark', 'label' => 'रिमार्क', 'type' => 'text', 'is_static' => true, 'order' => 1700],
            ];
        }

        // Convert array to object to mimic Eloquent Model
        return collect($fields)->map(function ($field) use ($formType) {
            $field['form_type'] = $formType;
            $field['is_required'] = true;
            $field['status'] = true;
            $field['options'] = null;
            return (object) $field;
        });
    }
}
