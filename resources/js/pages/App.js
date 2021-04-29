import React from "react";
import ReactDOM from "react-dom";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { Footer, Header, SubHedaer } from "../components";
import "../assets/styles/global.css";
import "@fontsource/montserrat";
import { About, Home, Services, Works } from ".";

function App() {
    return (
        <BrowserRouter>
            <div>
                <Header />
                <SubHedaer />
                <Switch>
                    <Route exact path="/" component={Home} />
                    <Route path="/about" component={About} />
                    <Route path="/works" component={Works} />
                    <Route path="/services" component={Services} />
                </Switch>
                <Footer />
            </div>
        </BrowserRouter>
    );
}

export default App;

if (document.getElementById("root")) {
    ReactDOM.render(<App />, document.getElementById("root"));
}
