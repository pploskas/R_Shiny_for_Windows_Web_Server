library(shiny)
library(leaflet)

r_colors <- rgb(t(col2rgb(colors()) / 255))
names(r_colors) <- colors()

ui <- fluidPage(
  

  titlePanel("", windowTitle = "testApp"),

  
  leafletOutput("mymap", height = "95vh", width = "100%"),
  p(),
  actionButton("recalc", "New points")
)

server <- function(input, output, session) {
  
  

  
  
  points <- eventReactive(input$recalc, {
    cbind(rnorm(40) * 2 + 13, rnorm(40) + 48)
  }, ignoreNULL = FALSE)
  
  output$mymap <- renderLeaflet({
    leaflet() %>%
      addProviderTiles(providers$CartoDB.Positron,
                       options = providerTileOptions(noWrap = TRUE)
      ) %>%
      addMarkers(data = points())
  })

  

}

shinyApp(ui, server)