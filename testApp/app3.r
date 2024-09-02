library(shiny)
library(leaflet)

#instance point
options(shiny.port =50003 )
options(shiny.host = "10.38.23.51")


r_colors <- rgb(t(col2rgb(colors()) / 255))
names(r_colors) <- colors()

ui <- fluidPage(
  # core mechanism point
  hidden(
    div(
      actionButton("ENTER_BUTTON", "ENTER_BUTTON")
    )
  ),
  
  titlePanel("", windowTitle = "testApp"),

  
  leafletOutput("mymap", height = "95vh", width = "100%"),
  p(),
  actionButton("recalc", "New points")

  )

server <- function(input, output, session) {
  

  
  # check idle point
  idle_counter<-0
  pre_TIME<-Sys.time()
  autoInvalidate <- reactiveTimer(500)
  
  # core mechanism point
  click("ENTER_BUTTON")
  
  observeEvent(input$ENTER_BUTTON, {
    part1<-"NULL"
    temp_text<-"NULL"

    temp_text<-session$clientData$url_search
    if(trimws(temp_text)!="")
      {
      temp_text<-trimws(stri_replace_all_fixed(temp_text, "?", ""))
      temp_frame<-data.frame(strsplit(toString(temp_text), ";",fixed=TRUE))
      part1<-trimws(temp_frame[[1]][1])
      }
    
    #instance point
    file_name<-"the_password3.txt"  
    con = file(file_name, "r")
    line = trimws(readLines(con, n = 1))
    close(con)
    if(line!=part1)   {
      alert("wrong password")
      stopApp()
    }
  })
  
  
  
  points <- eventReactive(input$recalc, {
    # check idle point
    idle_counter<<-0
    
    cbind(rnorm(40) * 2 + 13, rnorm(40) + 48)
  }, ignoreNULL = FALSE)
  
  output$mymap <- renderLeaflet({
    leaflet() %>%
      addProviderTiles(providers$CartoDB.Positron,
                       options = providerTileOptions(noWrap = TRUE)
      ) %>%
      addMarkers(data = points())
  })

  
  
  # check idle point
  observe({
    cur_TIME<-autoInvalidate()
    atime<-as.numeric((cur_TIME-pre_TIME),units="secs")
    if(atime>=0.4)
    {
      idle_counter<<-(idle_counter+1)
      if(idle_counter==600)
      {
        stopApp()
      }
    }
    atime<-as.numeric((cur_TIME-pre_TIME),units="secs")
    pre_TIME<<-Sys.time()+(0.5-atime)
  })    
  
  
  # check idle point
    session$onSessionEnded(function() {
    stopApp()
  })
  
  
    
}

shinyApp(ui, server)