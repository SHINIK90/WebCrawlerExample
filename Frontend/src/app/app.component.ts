import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet } from '@angular/router';
import { ApiService } from './api.service';
import { HttpClientModule } from '@angular/common/http';
import { MatButtonModule } from '@angular/material/button';
import { MatTable, MatTableDataSource } from '@angular/material/table';
import { MatIconModule } from '@angular/material/icon';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, CommonModule, HttpClientModule, MatButtonModule, MatTable, MatIconModule],
  templateUrl: './app.component.html',
  styleUrl: './app.component.scss'
})
export class AppComponent implements OnInit {
  entries: any[] = [];
  currentDirection:string = 'desc';
  displayedColumns: string[] = ['rank', 'title', 'points', 'comments'];
  dataSource: MatTableDataSource<any>;


  constructor(private cdr: ChangeDetectorRef, private apiService: ApiService) {this.dataSource = new MatTableDataSource();}

  ngOnInit(): void {
    this.getEntries('ALL');
    
  }

  getEntries(option:string, direction?:string, word_limit?:number){
    this.apiService.getEntries(option, direction?direction:'desc', word_limit? word_limit:5).subscribe({
      next: (response:any) => {
        // console.log('API Response:', response);
        this.entries = response || [];
        this.dataSource.data = response;
        this.cdr.detectChanges();
      },
      error: (error:any) => {
        console.error('Error getting entries ', error);
      }
    });
  }
  filterComments(){
    this.getEntries('FILTER_COMMENTS', this.currentDirection);
  }
  filterPoints(){
    this.getEntries('FILTER_POINTS', this.currentDirection);
  }
  toggleSortDirection(){
    if(this.currentDirection === 'asc'){
      this.currentDirection = 'desc';
    }else{
      this.currentDirection = 'asc';
    }
  }
}
