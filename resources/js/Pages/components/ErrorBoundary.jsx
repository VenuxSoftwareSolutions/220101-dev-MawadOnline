import React from "react";

export class ErrorBoundary extends React.Component {
    constructor(props) {
        super(props);
        this.state = { hasError: false };
    }

    static getDerivedStateFromError(_error) {
        return { hasError: true };
    }

    componentDidCatch(error, errorInfo) {
        console.error("Error in component:", error, errorInfo);
    }

    render() {
        if (this.state.hasError) {
            return (
                <div className="d-flex justify-content-center align-items-center flex-column alert alert-danger">
                    <h3>⚠️ Something went wrong.</h3>
                    <p>Try refreshing the page or contact support.</p>
                </div>
            );
        }

        return this.props.children;
    }
}
