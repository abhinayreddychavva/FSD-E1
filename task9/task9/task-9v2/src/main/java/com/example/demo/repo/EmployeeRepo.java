package com.example.demo.repo;

import java.util.ArrayList;
import java.util.List;

import com.example.demo.model.Employee;
public class EmployeeRepo {
	List<Employee>employeeList=new ArrayList<>();
	public void addEmployee(Employee emp) {
		employeeList.add(emp);
	}
	public List<Employee> getAllEmployees(){
		return employeeList;
	}
	public Employee getEmployee(int id) {
		Employee employee=new Employee();
		for(Employee emp:employeeList) {
			if(emp.getId()==id) {
				employee=emp;
			}
		}
		return employee;
	}
}
