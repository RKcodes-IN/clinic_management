<?php

namespace App\Imports;

use App\Models\PatientDetail;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class PatientsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
{

    // Check if the user already exists by phone number or email

    if(!empty($row['email_id'])){

        $existingUser = User::where('phone', $row['phone_no'])
        ->orWhere('email', $row['email_id'])
        ->first();

    }else{
        $existingUser = User::where('phone', $row['phone_no'])
        ->first();
    }
    
    // If the user exists in the User table but not in PatientDetail, create a PatientDetail record
    if ($existingUser) {
        $existingPatient = PatientDetail::where('user_id', $existingUser->id)->first();
        
        if (!$existingPatient) {
            // Create a new PatientDetail entry linked to the existing user
            return new PatientDetail([
                'user_id' => $existingUser->id,
                'name' => $row['first_name'] . ' ' . $row['last_name'],
                'phone_number' => $row['phone_no'],
                'date_of_birth' => $row['date_of_birth'],
                'address' => $row['address'],
                'place' => $row['place' ]??"na",
                'city' => $row['city']??"",

                'state' => $row['state'],
                'country' => $row['country'],
                'whatsapp_no' => isset($row['whatsapp_no'])?$row['whatsapp_no']:0,

                'age' => $row['age'],
                'gender' => $row['gender'],
                'pincode' => $row['pincode'],
                // Add other patient attributes as needed
            ]);
        }
        
        // If both User and PatientDetail records exist, skip importing
        return null;
    }

    // Retrieve the "patient" role
    $role = Role::where('name', 'patient')->firstOrFail();
    
    // Create a new User with the given role
    $user = User::create([
        'name'     => $row['first_name'] . ' ' . $row['last_name'],
        'email'    => $row['email_id'],
        'phone'    => $row['phone_no'],
        'user_role' => $role->name,
        // Add other user-related fields as needed
    ]);

    // Assign the role and permissions to the user
    $user->assignRole($role->name);
    $user->syncPermissions($role->permissions->pluck('name'));

    // Create a new PatientDetail entry for the new user
    return new PatientDetail([
        'user_id' => $user->id,
        'name' => $row['first_name'] . ' ' . $row['last_name'],
        'phone_number' => $row['phone_no'],
        'date_of_birth' => $row['date_of_birth'],
        'address' => $row['address'],
        'place' => $row['place' ]??"na",

        'city' => $row['city']??"",
        'state' => isset($row['state'])?$row['state']:"na",
        'country' => $row['country'],
        'whatsapp_no' => isset($row['whatsapp_no'])?$row['whatsapp_no']:0,
        'age' => $row['age'],
        'gender' => $row['gender'],
        'pincode' => $row['pincode'],
        // Add other patient attributes as needed
    ]);
}

}
