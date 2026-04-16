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
	@PostMapping
	public student createStudent(@RequestBody student student) {
	    return service.saveStudent(student);
	}
	@GetMapping
	public List<student>getAllStudents(){
		return service.getAllstudents();
	}
	@GetMapping("/{id}")
	public student getStudentById(@PathVariable int id) {
		return service.getstudentbyid(id);
	}
	@PutMapping("/{id}")
	public student updateStudent(@PathVariable int id,@RequestBody student student) {
		student.setId(id);
		 return service.updateStudent(student);
	}
	@DeleteMapping("/{id}")
	public String deleteStudent(@PathVariable int id) {
		service.deleteStudent(id);
		return "Student deleted Successfully";
	}


}
