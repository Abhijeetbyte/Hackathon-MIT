#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "iot"; // Replace with your SSID
const char* server = "beta-version-1.000webhostapp.com";
const String filename = "I001D";
const int sensorPin = 5;  // GPIO pin connected to the sensor
volatile unsigned int pulseCount = 0;
unsigned long previousMillis = 0;
const unsigned long sampleInterval = 1000;  // 1 second
const float calibrationFactor = 1.0;  // Adjust this value based on your sensor

void pulseCounter() {
  pulseCount++;
}

void setup() {
  Serial.begin(115200);
  pinMode(sensorPin, INPUT);
  attachInterrupt(digitalPinToInterrupt(sensorPin), pulseCounter, FALLING);
  
  // Connect to Wi-Fi
  WiFi.begin(ssid);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}

void loop() {
  unsigned long currentMillis = millis();

  if (currentMillis - previousMillis >= sampleInterval) {
    detachInterrupt(sensorPin);
    float flowRate = (pulseCount / calibrationFactor) / (sampleInterval / 1000.0);
    unsigned int totalMilliLitres = (flowRate * 60) * 1000;
    
    Serial.print("Flow rate: ");
    Serial.print(flowRate);
    Serial.print(" L/hour\tTotal Volume: ");
    Serial.print(totalMilliLitres);
    Serial.println(" mL");
    
    sendToServer(flowRate, totalMilliLitres);
    
    pulseCount = 0;
    previousMillis = currentMillis;
    attachInterrupt(digitalPinToInterrupt(sensorPin), pulseCounter, FALLING);
  }
}

void sendToServer(float flowRate, unsigned int totalVolume) {
  HTTPClient http;

  String url = "/iot-device.php"; // Adjust this to your API endpoint
  url += "?flowrate=" + String(flowRate);
  url += "&total_volume=" + String(totalVolume);
  url += "&filename=" + filename; // Include the filename parameter

  http.begin(server, 80, url);

  int httpResponseCode = http.GET();

  if (httpResponseCode > 0) {
    Serial.println("HTTP Response code: " + String(httpResponseCode));
  } else {
    Serial.println("Error in HTTP request");
  }

  http.end();
}
