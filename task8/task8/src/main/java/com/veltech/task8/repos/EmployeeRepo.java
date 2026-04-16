package com.veltech.task8.repos;
import java.util.*;

import org.springframework.stereotype.Component;

import com.veltech.task8.model.Employee;
@Component
public class EmployeeRepo {
	List<Employee>emplist=new ArrayList<>();
	public void addEmployee(Employee emp) {
		emplist.add(emp);
		
		
	}
	public Employee getEmployee(int id) {
		Employee i=new Employee();
		for(Employee emp:emplist) {
			if(emp.getId()==id) {
				i=emp;
				break;
			}
		}
		return i;
	}
	public List<Employee> getAllEmployees() {
		return emplist;
	}

}
