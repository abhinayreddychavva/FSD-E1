package com.veltech.task8.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import com.veltech.task8.model.Employee;
import com.veltech.task8.repos.EmployeeRepo;

@Component
public class EmployeeController {
	@Autowired
	public EmployeeRepo repo;
	public void addEmp(int id,String name,String dept) {
		repo.addEmployee(new Employee(id,name,dept));
	}
	public void getAll() {
		repo.getAllEmployees().forEach(System.out::println);
	}
}
