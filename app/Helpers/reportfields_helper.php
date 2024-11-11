<?php
function getSelectedClauses($fields)
{
    $reportFields = [
        [
            'fieldName' => 'livestockTagId',
            'clause' => "COALESCE(NULLIF(livestocks.livestock_tag_id, ''), 'Untagged') as livestockTagId"
        ],
        [
            'fieldName' => 'livestockTypeName',
            'clause' => "livestock_types.livestock_type_name as livestockTypeName"
        ],
        [
            'fieldName' => 'livestockAgeClassification',
            'clause' => "livestock_age_class.livestock_age_classification as livestockAgeClassification"
        ],
        [
            'fieldName' => 'livestockBreedName',
            'clause' => "livestock_breeds.livestock_breed_name as livestockBreedName"
        ],
        [
            'fieldName' => 'farmerUserId',
            'clause' => "user_accounts.user_id as farmerUserId",
        ],
        [
            'fieldName' => 'farmerName',
            'clause' => "CONCAT(user_accounts.first_name, ' ', user_accounts.last_name) as farmerName",
        ],
        [
            'fieldName' => 'farmerFirstName',
            'clause' => "user_accounts.first_name as farmerFirstName",
        ],
        [
            'fieldName' => 'farmerMiddleName',
            'clause' => "user_accounts.middle_name as farmerMiddleName",
        ],
        [
            'fieldName' => 'farmerLastName',
            'clause' => "user_accounts.last_name as farmerLastName",
        ],
        [
            'fieldName' => 'fullAddress',
            'clause' => "CONCAT_WS(', ', user_accounts.sitio, user_accounts.barangay, user_accounts.city, user_accounts.province) as fullAddress",
        ],
        [
            'fieldName' => 'sitio',
            'clause' => "user_accounts.sitio",
        ],
        [
            'fieldName' => 'barangay',
            'clause' => "user_accounts.barangay",
        ],
        [
            'fieldName' => 'city',
            'clause' => "user_accounts.city",
        ],
        [
            'fieldName' => 'province',
            'clause' => "user_accounts.province",
        ],
        [
            'fieldName' => 'age',
            'clause' => "CASE WHEN livestocks.age_years > 0 THEN CONCAT(livestocks.age_years, ' years') WHEN livestocks.age_months > 0 THEN CONCAT(livestocks.age_months, ' months') WHEN livestocks.age_weeks > 0 THEN CONCAT(livestocks.age_weeks, ' weeks') WHEN livestocks.age_days > 0 THEN CONCAT(livestocks.age_days, ' days') ELSE 'Unknown Age' END as age",
        ],
        [
            'fieldName' => 'ageDays',
            'clause' => "livestocks.age_days as ageDays",
        ],
        [
            'fieldName' => 'ageWeeks',
            'clause' => "livestocks.age_weeks as ageWeeks",
        ],
        [
            'fieldName' => 'ageMonths',
            'clause' => "livestocks.age_months as ageMonths",
        ],
        [
            'fieldName' => 'ageYears',
            'clause' => "livestocks.age_years as ageYears",
        ],
        [
            'fieldName' => 'sex',
            'clause' => "livestocks.sex",
        ],
        [
            'fieldName' => 'breedingEligibility',
            'clause' => "livestocks.breeding_eligibility as breedingEligibility",
        ],
        [
            'fieldName' => 'ldateOfBirth',
            'clause' => "livestocks.date_of_birth as ldateOfBirth",
        ],
        [
            'fieldName' => 'livestockHealthStatus',
            'clause' => "livestocks.livestock_health_status as livestockHealthStatus",
        ],
        [
            'fieldName' => 'origin',
            'clause' => "livestocks.origin",
        ],
        [
            'fieldName' => 'id',
            'clause' => "ROW_NUMBER() OVER () AS id",
        ],
        [
            'fieldName' => 'livestockCount',
            'clause' => "COUNT(*) as livestockCount",
        ],
        [
            'fieldName' => 'acquiredDate',
            'clause' => "farmer_livestocks.acquired_date as acquiredDate",
        ],
        [
            'fieldName' => 'udateOfBirth',
            'clause' => "user_accounts.date_of_birth as udateOfBirth",
        ],
        [
            'fieldName' => 'phoneNumber',
            'clause' => "user_accounts.phone_number as phoneNumber",
        ],
        [
            'fieldName' => 'aliveCount',
            'clause' => "SUM(CASE WHEN livestocks.livestock_health_status = 'Alive' THEN 1 ELSE 0 END) as aliveCount",
        ],
        [
            'fieldName' => 'sickCount',
            'clause' => "SUM(CASE WHEN livestocks.livestock_health_status = 'Sick' THEN 1 ELSE 0 END) as sickCount",
        ],
        [
            'fieldName' => 'deadCount',
            'clause' => "SUM(CASE WHEN livestocks.livestock_health_status = 'Dead' THEN 1 ELSE 0 END) as deadCount",
        ],
        [
            'fieldName' => 'action',
            'clause' => "farmer_audit.action",
        ],
        [
            'fieldName' => 'title',
            'clause' => "farmer_audit.title",
        ],
        [
            'fieldName' => 'description',
            'clause' => "farmer_audit.description",
        ],
        [
            'fieldName' => 'entityAffected',
            'clause' => "farmer_audit.entity_affected as entityAffected",
        ],
        [
            'fieldName' => 'timestamp',
            'clause' => "farmer_audit.timestamp",
        ],
        [
            'fieldName' => 'maleLivestockTagId',
            'clause' => "livestock_breedings.male_livestock_tag_id as maleLivestockTagId",
        ],
        [
            'fieldName' => 'femaleLivestockTagId',
            'clause' => "livestock_breedings.female_livestock_tag_id as femaleLivestockTagId",
        ],
        [
            'fieldName' => 'breedingResult',
            'clause' => "livestock_breedings.breeding_result as breedingResult",
        ],
        [
            'fieldName' => 'lbremarks',
            'clause' => "livestock_breedings.breeding_remarks as lbremarks",
        ],
        [
            'fieldName' => 'breedDate',
            'clause' => "livestock_breedings.breed_date as breedDate",
        ],
        [
            'fieldName' => 'outcome',
            'clause' => "livestock_pregnancies.outcome",
        ],
        [
            'fieldName' => 'pregnancyStartDate',
            'clause' => "livestock_pregnancies.pregnancy_start_date as pregnancyStartDate",
        ],
        [
            'fieldName' => 'expectedDeliveryDate',
            'clause' => "livestock_pregnancies.expected_delivery_date as expectedDeliveryDate",
        ],
        [
            'fieldName' => 'actualDeliveryDate',
            'clause' => "livestock_pregnancies.actual_delivery_date as actualDeliveryDate",
        ],
        [
            'fieldName' => 'causeOfDeath',
            'clause' => "livestock_mortalities.cause_of_death as causeOfDeath",
        ],
        [
            'fieldName' => 'lmremarks',
            'clause' => "livestock_mortalities.mortality_remarks as lmremarks",
        ],
        [
            'fieldName' => 'dateOfDeath',
            'clause' => "livestock_mortalities.date_of_death as dateOfDeath",
        ],
        [
            'fieldName' => 'vaccinationName',
            'clause' => "livestock_vaccinations.vaccination_name as vaccinationName",
        ],
        [
            'fieldName' => 'vaccinationDescription',
            'clause' => "livestock_vaccinations.vaccination_description as vaccinationDescription",
        ],
        [
            'fieldName' => 'lvremarks',
            'clause' => "livestock_vaccinations.vaccination_remarks as lvremarks",
        ],
        [
            'fieldName' => 'vaccinationDate',
            'clause' => "livestock_vaccinations.vaccination_date as vaccinationDate",
        ],
        [
            'fieldName' => 'batchGroupName',
            'clause' => "egg_production_batch_group.batch_name as batchGroupName",
        ],
        [
            'fieldName' => 'eggsProduced',
            'clause' => "livestock_egg_productions.eggs_produced as eggsProduced",
        ],
        [
            'fieldName' => 'lepremarks',
            'clause' => "livestock_egg_productions.lepremarks",
        ],
        [
            'fieldName' => 'dateOfProduction',
            'clause' => "livestock_egg_productions.date_of_production as dateOfProduction",
        ],
        [
            'fieldName' => 'batchDate',
            'clause' => "egg_processing_batch.batch_date as batchDate",
        ],
        [
            'fieldName' => 'machine',
            'clause' => "egg_processing_batch.machine",
        ],
        [
            'fieldName' => 'totalEggs',
            'clause' => "egg_processing_batch.total_eggs as totalEggs",
        ],
        [
            'fieldName' => 'mortalities',
            'clause' => "egg_processing_batch.mortalities",
        ],
        [
            'fieldName' => 'producedPoultry',
            'clause' => "egg_processing_batch.produced_poultry as producedPoultry",
        ],
        [
            'fieldName' => 'pepremarks',
            'clause' => "egg_processing_batch.remarks as pepremarks",
        ],
        [
            'fieldName' => 'status',
            'clause' => "egg_processing_batch.status",
        ],
        [
            'fieldName' => 'userName',
            'clause' => "CONCAT(user_accounts.first_name, ' ', user_accounts.last_name) as userName",
        ],
        [
            'fieldName' => 'pemaction',
            'clause' => "egg_monitoring_logs.action as pemaction",
        ],
        [
            'fieldName' => 'emlremarks',
            'clause' => "egg_monitoring_logs.remarks as emlremarks",
        ],
        [
            'fieldName' => 'dateConducted',
            'clause' => "egg_monitoring_logs.date_conducted as dateConducted",
        ]
    ];

    $selectedClauses = [];

    // Check if $fields is an array before looping
    if (is_array($fields)) {
        foreach ($fields as $field) {
            // Check if the field exists in the $reportFields array
            foreach ($reportFields as $reportField) {
                if ($field === $reportField['fieldName']) {
                    // Add the clause if field matches
                    $selectedClauses[] = $reportField['clause'];
                    break;
                }
            }
        }
    }

    return $selectedClauses;
}


function getGroupByFields($fields)
{
    $groupByFields = [
        [
            'fieldName' => 'farmerUserId',
            'gbField' => 'user_accounts.user_id'
        ],
        [
            'fieldName' => 'farmerName',
            'gbField' => 'user_accounts.user_id'
        ],
        [
            'fieldName' => 'farmerFirstName',
            'gbField' => "user_accounts.first_name",
        ],
        [
            'fieldName' => 'farmerMiddleName',
            'gbField' => "user_accounts.middle_name",
        ],
        [
            'fieldName' => 'farmerLastName',
            'gbField' => "user_accounts.last_name",
        ],
        [
            'fieldName' => 'fullAddress',
            'gbField' => "fullAddress",
        ],
        [
            'fieldName' => 'sitio',
            'gbField' => "user_accounts.sitio",
        ],
        [
            'fieldName' => 'barangay',
            'gbField' => "user_accounts.barangay",
        ],
        [
            'fieldName' => 'city',
            'gbField' => "user_accounts.city",
        ],
        [
            'fieldName' => 'province',
            'gbField' => "user_accounts.province",
        ],
        [
            'fieldName' => 'acquiredDate',
            'gbField' => "farmer_livestocks.acquired_date",
        ],
        [
            'fieldName' => 'udateOfBirth',
            'gbField' => "user_accounts.date_of_birth",
        ],
        [
            'fieldName' => 'phoneNumber',
            'gbField' => "user_accounts.phone_number",
        ],
        [
            'fieldName' => 'livestockTypeName',
            'gbField' => "livestock_types.livestock_type_name"
        ],
        [
            'fieldName' => 'livestockAgeClassification',
            'gbField' => "livestock_age_class.livestock_age_classification"
        ],
        [
            'fieldName' => 'livestockBreedName',
            'gbField' => "livestock_breeds.livestock_breed_name"
        ],
    ];

    $selectedFields = [];

    // Check if $fields is an array before looping
    if (is_array($fields)) {
        foreach ($fields as $field) {
            // Check if the field exists in the $reportFields array
            foreach ($groupByFields as $groupByField) {
                if ($field === $groupByField['fieldName']) {
                    // Add the clause if field matches
                    $selectedFields[] = $groupByField['gbField'];
                    break;
                }
            }
        }
    }

    return $selectedFields;
}