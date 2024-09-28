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
    this.getEntries('ALL');                                                               //Start the view with the table populated with all entries
  }

  getEntries(option:string, direction?:string, word_limit?:number){                       //General function to request entries array from the apiService given option, direction, and word limit
    this.apiService.getEntries(option, direction?direction:'desc', word_limit? word_limit:5).subscribe({
      next: (response:any) => {
        // console.log('API Response:', response);
        this.entries = response || [];                                                    //save the array to a local variable
        this.dataSource.data = response;                                                  //populate the table with the entries array
        this.cdr.detectChanges();                                                         //detect changes and refresh the view
      },
      error: (error:any) => {
        console.error('Error getting entries ', error);
      }
    });
  }
  filterComments(){                                                                       //function for the comments filter button, using the current direction to sort
    this.getEntries('FILTER_COMMENTS', this.currentDirection);
  }
  filterPoints(){                                                                         //function for the points filter button, using the current direction to sort
    this.getEntries('FILTER_POINTS', this.currentDirection);
  }
  toggleSortDirection(){                                                                  //function for the sorting direction button
    if(this.currentDirection === 'asc'){
      this.currentDirection = 'desc';
    }else{
      this.currentDirection = 'asc';
    }
  }
}
