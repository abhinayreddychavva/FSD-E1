package com.example.demo.controller;

import java.util.List;

import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.PutMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.example.demo.model.student;
import com.example.demo.service.StudentService;

@RestController
@RequestMapping("/students")
public class StudentController {
	private final StudentService service;
	public StudentController(StudentService service) {
		this.service = service;
	}
	@GetMapping("/dept/{dept}")
	public List<student>getByDept(@PathVariable("dept")String dept){
		return service.findByDept(dept);
	}
	@GetMapping("/age/{age}")
	public List<student>getByAge(@PathVariable("age")int age){
		return service.findByAge(age);
	}
	@GetMapping("/ageGreater/{age}")
	public List<student>getByAgeGreaterThan(@PathVariable("age")int age){
		return service.findByAgeGreaterThan(age);
	}
	@GetMapping("/ageAndDept/{age},{dept}")
	public List<student>getByAgeAndDept(@PathVariable("age")int age,@PathVariable("dept")String dept){
		return service.findByDepartmentAndAge(dept, age);
	}
	
}