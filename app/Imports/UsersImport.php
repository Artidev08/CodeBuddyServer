<?php
  
namespace App\Imports;
  
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;
use Illuminate\Validation\ValidationException;
  
class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $emailExists = User::where('email', $row['email'])->exists();
        if ($row['first_name'] == null) {
            throw ValidationException::withMessages(['First Name can not be null']);
        }
        if (!$emailExists) {
            $user = new User(
                [
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email'  => $row['email'],
                'password' => Hash::make($row['password']),
                'phone' => $row['phone_number'],
                ]
            );
            $user->save();
            $user->syncRoles([$row['assign_role']]);
            return $user;
        }
    }
}
