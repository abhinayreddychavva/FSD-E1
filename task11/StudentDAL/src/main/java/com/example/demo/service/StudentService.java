package com.example.demo.service;

import java.util.List;

import org.springframework.stereotype.Service;

import com.example.demo.model.student;
import com.example.demo.repository.StudentRepo;

@Service
public class StudentService {
	private final StudentRepo repo;

	public StudentService(StudentRepo repo) {
		this.repo = repo;
	}
	
	public student saveStudent(student s) {
		repo.save(s);
		return s;
	}
	public List<student> getAllStudents(){
		return repo.findAll();
	}
	public student getStudent(int id) {
		return repo.findById(id).orElse(null);
	}
	public student updateStudent(student s) {
		return repo.save(s);
	}
	public void deleteStudent(int id) {
		repo.deleteById(id);
	}
//	public List<Student> findByDept(String dept){
//		return repo.findAll().stream().filter(x->x.getDept()==dept).toList();
//	}
//	public List<Student> findByAge(int age){
//		return repo.findAll().stream().filter(x->x.getAge()==age).toList();
//	}
//	public List<Student> findAgeGreaterThan(int age){
//		return repo.findAll().stream().filter(x->x.getAge()>age).toList();
//	}
	public List<student>findByDept(String dept){
		return repo.findByDept(dept);
	}
	public List<student>findByAge(int age){
		return repo.findByAge(age);
	}
	public List<student>findByAgeGreaterThan(int age){
		return repo.findByAgeGreaterThan(age);
	}
	public List<student>findByDepartmentAndAge(String dept,int age){
		return repo.findByDeptAndAge(dept, age);
	}
}