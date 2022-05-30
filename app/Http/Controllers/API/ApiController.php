<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    // CREATE API - POST
    public function createEmployee(Request $request)
    {
        // validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees',
            'phone_no' => 'required',
            'age' => 'required',
            'gender' => 'required',
        ]);

        // save instance to db
        Employee::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'phone_no' => $request->phone_no,
                'age' => $request->age,
                'gender' => $request->gender,
            ]
        );

        // return response 
        return response()->json(
            [
                'status' => 1,
                'message' => 'Employee Created Successfully'
            ]
        );
    }

    // LIST API - GET
    public function listEmployees()
    {
        // get all data
        $employees = Employee::get();

        // return response
        return response()->json([
            'status' => 1,
            'message' => 'Listing Employees',
            'data' => $employees
        ], 200);
    }

    // SINGLE DETAILED API - GET
    public function getEmployee($id)
    {
        // check if data exist
        return Employee::where('id', $id)->exist() ?
            // valid request
            response()->json([
                'status' => 1,
                'message' => 'Employee found ok',
                'data' => Employee::where('id', $id)->first()
            ], 200)

            :

            // Invalid request
            response()->json([
                'status' => 0,
                'message' => 'Employee not found'
            ], 404);
    }

    // UPDATE API - PUT
    public function updateEmployee(Request $request, $id)
    {
        // check if data exist
        if (Employee::where('id', $id)->exist()) {

            $employee = Employee::find($id);

            $employee->name = !empty($request->name) ? $request->name : $employee->name;
            $employee->email = !empty($request->email) ? $request->email : $employee->email;
            $employee->age = !empty($request->age) ? $request->age : $employee->age;
            $employee->gender = !empty($request->gender) ? $request->gender : $employee->gender;
            $employee->phone_no = !empty($request->phone_no) ? $request->phone_no : $employee->phone_no;

            $employee->save();

            return response()->json(
                [
                    'status' => 1,
                    'message' => 'Employee Updated Successfully'
                ]
            );
        } else {
            response()->json([
                'status' => 0,
                'message' => 'Employee not found'
            ], 404);
        }
    }

    // DELETE API - DELETE
    public function deleteEmployee($id)
    {
        // check if data exist
        if (Employee::where('id', $id)->exist()) {

            $employee = Employee::find($id);

            $employee->delete();

            return response()->json(
                [
                    'status' => 1,
                    'message' => 'Employee Deleted Successfully'
                ],
                200
            );
        } else {
            response()->json([
                'status' => 0,
                'message' => 'Employee not found'
            ], 404);
        }
    }
}
