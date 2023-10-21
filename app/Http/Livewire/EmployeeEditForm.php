<?php

namespace App\Http\Livewire;

use App\Http\Traits\useUniqueValidation;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EmployeeEditForm extends Component
{
    use useUniqueValidation;

    public $employees;
    public Collection $roles;
    public Collection $positions;

    public function mount(Collection $employees)
    {
        $this->employees = []; // reset, karena ada data employees sebelumnya

        foreach ($employees as $employee) {
            $this->employees[] = [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'original_email' => $employee->email, // untuk cek validasi unique
                'role_id' => $employee->role_id,
                'position_id' => $employee->position_id
            ];
        }
        $this->roles = Role::all();
        $this->positions = Position::all();
    }
    public function saveEmployees()
    {
        $roleIdRuleIn = join(',', $this->roles->pluck('id')->toArray());
        $positionIdRuleIn = join(',', $this->positions->pluck('id')->toArray());

        $this->validate([
            'employees.*.name' => 'required',
            'employees.*.email' => 'required|email',
            'employees.*.password' => '',
            'employees.*.role_id' => 'required|in:' . $roleIdRuleIn,
            'employees.*.position_id' => 'required|in:' . $positionIdRuleIn,
        ]);


        if (!$this->isUniqueOnLocal('email', $this->employees)) {
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input Email tidak mangandung nilai yang sama dengan input lainnya.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;
        foreach ($this->employees as $employee) {
            // cek unique validasi
            $employeeBeforeUpdated = User::find($employee['id']);

            if (!$this->isUniqueOnDatabase($employeeBeforeUpdated, $employee, 'email', User::class)) {
                $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
                return session()->flash('failed', "Email dari data karyawaan {$employee['id']} sudah terdaftar. Silahkan masukan email yang berbeda!");
            }

            $affected += $employeeBeforeUpdated->update([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'role_id' => $employee['role_id'],
                'position_id' => $employee['position_id'],
            ]);
        }

        $message = $affected === 0 ?
            "Tidak ada data karyawaan yang diubah." :
            "Ada $affected data karyawaan yang berhasil diedit.";

        return redirect()->route('users.index')->with('success', $message);
    }

    public function render()
    {
        return view('livewire.employee-edit-form');
    }
}
